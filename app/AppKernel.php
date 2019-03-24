<?php

namespace App;

use Http\Middleware\MiddlewareAllowMethod;
use Http\Middleware\MiddlewareController;
use Http\Middleware\MiddlewarePreController;
use Http\Middleware\MiddlewareRouting;
use System\ES;

final class AppKernel
{
	/**
	 * @var array
	 */
	private $middlewares = [];

	/**
	 * @var array
	 */
	private $providers = [];

	/**
	 * AppKernel constructor.
	 */
	public function __construct()
	{
		ES::set(ES::APP_KERNEL, $this);
	}

	/**
	 * @return AppKernel
	 */
	public function installMiddlewares(): self
	{
        $this->commonMiddlewares();
        $this->customMiddlewares();
		return $this;
	}

	/**
	 * @return AppKernel
	 */
	public function installProviders(): self
	{
		return $this;
	}

	/**
	 * @return array
	 */
	public function getProviders(): array
	{
		return $this->providers;
	}

	/**
	 * @return array
	 */
	public function getMiddlewares(): array
	{
		return $this->middlewares;
	}

    /**
     * @return AppKernel
     */
    private function commonMiddlewares(): self
    {
        $this->middlewares[] = [
            'autoStart' => true,
            'class'     => MiddlewareRouting::class,
        ];

        $this->middlewares[] = [
            'autoStart' => true,
            'class'     => MiddlewareAllowMethod::class,
        ];

        $this->middlewares[] = [
            'autoStart' => true,
            'class'     => MiddlewarePreController::class,
        ];

        $this->middlewares[] = [
            'autoStart' => true,
            'class'     => MiddlewareController::class,
        ];

        return $this;
    }

    /**
     * @return AppKernel
     */
    private function customMiddlewares(): self
    {
        return $this;
    }
}