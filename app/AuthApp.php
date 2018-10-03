<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.10.2018
 * Time: 23:27
 */

namespace App;

use System\Kernel\TypesApp\AbstractApplication;
use Http\Request\ServerRequest;
use System\Logger\LogLevel;
use Exception\ExceptionListener\ExceptionListener;
use System\Database\DB;
use System\Logger\LoggerStorage;
use Http\Response\Response;
use System\EventListener\EventTypes;
use Http\Response\API;
use Http\Middleware\StorageMiddleware;
use Providers\StorageProviders;

class AuthApp extends AbstractApplication
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
	 * @param AppKernel $appKernel
	 * @return AbstractApplication
	 */
	public function setAppKernel(AppKernel $appKernel): AbstractApplication
	{
		parent::setAppKernel($appKernel);
		StorageProviders::add($appKernel->getProviders());
		StorageMiddleware::add($appKernel->getMiddlewares());

		return $this;
	}

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

	public function terminate()
	{
		DB::disconnect();
		LoggerStorage::create()->releaseLog();
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