<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.10.2018
 * Time: 23:27
 */

namespace App;

use System\EventListener\EventManager;
use System\Kernel\TypesApp\AbstractApplication;
use Http\Request\ServerRequest;
use System\Logger\LoggerElasticSearch;
use System\Logger\LogLevel;
use Exception\ExceptionListener\ExceptionListener;
use Http\Response\Response;
use System\EventListener\EventTypes;
use Http\Response\API;
use Http\Middleware\StorageMiddleware;
use Providers\StorageProviders;

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
	 * @throws \Exception\MiddlewareException
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
	 * @throws \Exception\FileException
	 * @throws \Throwable
	 */
	public function run()
	{
		$this->runInternal();

		try {
			$this->handle();
		} catch(\Throwable $e) {
			$this->log(LogLevel::ERROR, $e->getTraceAsString());
			new ExceptionListener($e);
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

	public function terminate()
	{
		LoggerElasticSearch::create()->releaseLog();
	}

	/**
	 * @param \Throwable $e
	 * @throws \Throwable
	 */
	public function customOutputError(\Throwable $e)
	{
		if ($this->env == self::ENV_TYPE['DEV']) {
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