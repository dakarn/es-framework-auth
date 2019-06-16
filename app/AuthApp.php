<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.10.2018
 * Time: 23:27
 */

namespace ES\App;

use ES\Kernel\EventListener\EventManager;
use ES\Kernel\Kernel\TypesApp\AbstractApplication;
use ES\Kernel\Http\Request\ServerRequest;
use ES\Kernel\Logger\LoggerElasticSearchStorage;
use ES\Kernel\Logger\LogLevel;
use ES\Kernel\Http\Response\Response;
use ES\Kernel\EventListener\EventTypes;
use ES\Kernel\Http\Response\API;
use ES\Kernel\Http\Middleware\StorageMiddleware;
use ES\Kernel\Providers\StorageProviders;

class AuthApp extends AbstractApplication implements AuthAppInterface
{
	const ERROR_500 = '500 Internal Server Error';

	/**
	 * @var  ServerRequest
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * @var AppKernel
	 */
	private $appKernel;

	/**
	 * @return AuthApp
	 * @throws \ES\Kernel\Exception\MiddlewareException
	 */
	public function handle(): AuthApp
	{
		$this->request = ServerRequest::fromGlobal()->handle();

		return $this;
	}

	/**
	 * @return void
	 */
	public function outputResponse(): void
	{
		$this->response = $this->request->resultHandle();

		$this->response->sendHeaders();
		$this->response->output();

		$this->eventManager->runEvent(EventTypes::AFTER_OUTPUT_RESPONSE);
	}

	/**
	 * @throws \Throwable
	 */
	public function run()
	{
		$this->runInternal();

		try {
			$this->handle();
		} catch(\Throwable $e) {
			$this->log(LogLevel::ERROR, $e->getTraceAsString());
			$this->outputException($e);
		}
	}

	/**
	 * @return void
	 */
	public function setupClass()
	{
		$appEvent = new AppEvent();
		$this->eventManager = $appEvent->installEvents(new EventManager());

		$this->appKernel = new AppKernel();
		$this->appKernel
			->installMiddlewares()
			->installProviders();

		StorageProviders::add($this->appKernel->getProviders());
		StorageMiddleware::add($this->appKernel->getMiddlewares());
	}

	/**
	 * @throws \ES\Kernel\Exception\FileException
	 * @throws \ES\Kernel\Exception\HttpException
	 */
	public function terminate()
	{
		LoggerElasticSearchStorage::create()->releaseLogs();
	}

	/**
	 * @param \Throwable $e
	 * @throws \Throwable
	 */
	public function customOutputError(\Throwable $e)
	{
		if ($this->env == self::ENV_DEV) {
			$error = 'Exception: ' . $e->getMessage() .' in ' . $e->getFile() . ' on line ' . $e->getLine();
		} else {
			$error = self::ERROR_500;
		}

		(new Response())
			->withHeader('Access-Control-Allow-Origin','*')
			->withHeader('Content-type','application/json')
			->withBody(new API([
				'success' => false,
				'error'   => $error,
			], [
				'type' => ''
			]))
			->output();

		exit;
	}
}