<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 22:38
 */

namespace App\Controller;

use Http\Response\Response;
use System\Controller\AbstractController;

class AuthController extends AbstractController
{
	public function accessTokenAction(): Response
	{
		return $this->responseApiOK([
			'accessToken'  => 'dfefesfejfheufheufheuheufyh47rye7fye7',
			'refreshToken' => 'dfdsfdsfdfdfdfdfdf',
			'expire_in'    => 14565565656,
		]);
	}

	public function refreshTokenAction()
	{

	}
}