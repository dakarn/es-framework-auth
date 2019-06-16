<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 22:38
 */

namespace ES\App\Controller;

use ES\Kernel\Auth\ClientAppRepository;
use ES\App\Validator\AuthAppValidator;
use ES\App\Validator\RefreshTokenValidator;
use ES\Kernel\Helper\RepositoryHelper\StorageRepository;
use ES\Kernel\Http\Response\Response;
use ES\Kernel\Models\User\User;
use ES\Kernel\Auth\Authentication\Authentication;
use ES\Kernel\Controller\AbstractController;
use ES\Kernel\Validators\Validators;

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

			return $this->responseApiOK($authResult->getCredentials());
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

			return $this->responseApiOK(Authentication::create()->getCredentials());
		} else {
			return $this->responseApiBad($validator->getErrorsApi());
		}
	}
}