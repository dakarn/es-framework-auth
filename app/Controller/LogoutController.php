<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 22:47
 */

namespace ES\App\Controller;

use ES\Kernel\Http\Response\Response;
use ES\Kernel\Models\User\User;
use ES\Kernel\Controller\AbstractController;
use ES\Kernel\Validators\Validators;

class LogoutController extends AbstractController
{
	/**
	 * @return Response
	 * @throws \ES\Kernel\Exception\FileException
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
	 * @throws \ES\Kernel\Exception\FileException
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