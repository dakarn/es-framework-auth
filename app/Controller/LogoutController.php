<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 22:47
 */

namespace App\Controller;

use Http\Response\Response;
use Models\User\User;
use System\Controller\AbstractController;
use System\Validators\Validators;

class LogoutController extends AbstractController
{
	/**
	 * @return Response
	 * @throws \Exception\FileException
	 */
	public function logoutAction(): Response
	{
		$accessToken = $this->request->takePost('accessToken');

		if (empty($accessToken)) {
			return $this->responseApiBadFormError('unknown-access', Validators::COMMON);
		}

		$user = User::current();

		if (!$user->isAuth()) {
			return $this->responseApiBadFormError('error-query', Validators::COMMON);
		}

		if (!$user->logout()) {
			return $this->responseApiBadFormError('error-query', Validators::COMMON);
		}

		return $this->responseApiOKFormMsg('success', Validators::SUCCESS);
	}

	/**
	 * @return Response
	 * @throws \Exception\FileException
	 */
	public function logoutAllGadgetAction(): Response
	{
		$accessToken = $this->request->takePost('accessToken');

		if (empty($accessToken)) {
			return $this->responseApiBadFormError('unknown-access', Validators::COMMON);
		}

		$user = User::current();

		if (!$user->isAuth()) {
			return $this->responseApiBadFormError('error-query', Validators::COMMON);
		}

		if (!$user->logoutAllDevice()) {
			return $this->responseApiBadFormError('error-query', Validators::COMMON);
		}

		return $this->responseApiOKFormMsg('success', Validators::SUCCESS);
	}
}