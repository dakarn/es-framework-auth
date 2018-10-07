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
use Http\Response\Response;
use Models\User\User;
use System\Auth\Authentication\Authentication;
use System\Controller\AbstractController;

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

			if ($authAppRepos->isLoaded()) {
				$user = User::current();
				$user->loadByEmailOrLogin($validator);

				if ($user->isLoaded()) {

					$authResult = Authentication::create()->processAuthentication($user, $authAppRepos->getResult()->getAccessTTL());

					if ($authResult->isAuth()) {
						return $this->responseApiOK($authResult->getCreds());
					}
				}
			}
		}

		return $this->responseApiBad($validator->getErrorsApi());

	}

	/**
	 * @return Response
	 */
	public function refreshTokenAction(): Response
	{
		return $this->responseApiOK([
			'accessToken'  => 'dfefesfejfheufheufheuheufyh47rye7fye7',
			'refreshToken' => 'dfdsfdsfdfdfdfdfdf',
			'expire_in'    => 14565565656,
		]);
	}
}