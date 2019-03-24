<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 22:38
 */

namespace App\Controller;

use System\Auth\ClientAppRepository;
use App\Validator\AuthAppValidator;
use App\Validator\RefreshTokenValidator;
use Helper\RepositoryHelper\StorageRepository;
use Http\Response\Response;
use Models\User\User;
use System\Auth\Authentication\Authentication;
use System\Controller\AbstractController;
use System\Validators\Validators;

class AuthController extends AbstractController
{
	/**
	 * @return Response
	 * @throws \Exception
	 */
	public function accessTokenAction(): Response
	{
		$validator = (new AuthAppValidator())->setUseIfPost();

		if ($validator->isValid()) {

			/** @var ClientAppRepository $authAppRepository */
			$authAppRepository = StorageRepository::getRepository(ClientAppRepository::class);
			$authAppRepository->loadClientApp($validator);

			if (!$authAppRepository->isLoaded()) {
				return $this->responseApiBadWithError($validator, 'unknown-clients', Validators::COMMON);
			}

			$user = User::loadByEmailOrLogin($validator);

			if (!$user->isLoaded()) {
				return $this->responseApiBad($user->getErrors());
			}

			$authResult = Authentication::create()->processAuthentication($user);

			if (!$authResult->isAuth()) {
				return $this->responseApiBadWithError($validator, 'error-query', Validators::COMMON);
			}

			return $this->responseApiOK($authResult->getCreds());
		} else {
			return $this->responseApiBad($validator->getErrorsApi());
		}
	}

	/**
	 * @return Response
	 * @throws \Exception
	 */
	public function refreshTokenAction(): Response
	{
		$validator = (new RefreshTokenValidator())->setUseIfPost();

		if ($validator->isValid()) {

			/** @var ClientAppRepository $clientAppRepository */
			$clientAppRepository = StorageRepository::getRepository(ClientAppRepository::class);
			$clientAppRepository->loadClientApp($validator);

			if (!$clientAppRepository->isLoaded()) {
				return $this->responseApiBadWithError($validator, 'unknown-clients', Validators::COMMON);
			}

			$updateResult = Authentication::create()->processUpdateRefreshToken($validator);

			if (!$updateResult->isUpdate()) {
				return $this->responseApiBad($validator->getErrorsApi());
			}

			return $this->responseApiOK(Authentication::create()->getCreds());
		} else {
			return $this->responseApiBad($validator->getErrorsApi());
		}
	}
}