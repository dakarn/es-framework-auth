<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 22:38
 */

namespace App\Controller;

use App\Models\AuthAppRepository;
use App\Validator\AuthAppValidator;
use App\Validator\RefreshTokenValidator;
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

			$authAppRepos = new AuthAppRepository();
			$authAppRepos->loadClientApp($validator);

			if (!$authAppRepos->isLoaded()) {
				return $this->responseApiBadWithError($validator, 'unknown-clients', Validators::COMMON);
			}

			$user = User::current();
			$user->loadByEmailOrLogin($validator);

			if (!$user->isLoaded()) {
				return $this->responseApiBad($user->getErrors());
			}

			$authResult = Authentication::create()->processAuthentication($user, $authAppRepos->getResult()->getAccessTTL());

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

			$authAppRepos = new AuthAppRepository();
			$authAppRepos->loadClientApp($validator);

			if (!$authAppRepos->isLoaded()) {
				return $this->responseApiBadWithError($validator, 'unknown-clients', Validators::COMMON);
			}

			$isUpdate = Authentication::create()->processUpdateRefreshToken($validator, $authAppRepos);

			if (!$isUpdate) {
				return $this->responseApiBad($validator->getErrorsApi());
			}

			return $this->responseApiOK(Authentication::create()->getCreds());
		} else {
			return $this->responseApiBad($validator->getErrorsApi());
		}
	}
}