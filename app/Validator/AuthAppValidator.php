<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.10.2018
 * Time: 22:03
 */

namespace App\Validator;

use Helper\Util;
use System\Validators\AbstractValidator;
use System\Validators\Validators;

class AuthAppValidator extends AbstractValidator
{
	/**
	 * @var bool
	 */
	public $isUseFlashErrors = false;

	/**
	 * @throws \Exception\FileException
	 */
	public function validate(): void
	{
		$message = Util::getFormMessage(Validators::AUTH_APP);

		$this->validateCSRFToken();

		if (!$this->isPost()) {
			$this->stackErrors['query'] = $message['query'];
		}

		if (empty($_POST['site'])) {
			$this->stackErrors['site'] = $message['site'];
		}

		if (empty($_POST['clientId'])) {
			$this->stackErrors['clientId'] = $message['clientId'];
		}

		if (empty($_POST['clientSecret'])) {
			$this->stackErrors['clientSecret'] = $message['clientSecret'];
		}

		if (empty($_POST['login'])) {
			$this->stackErrors['login'] = $message['login'];
		}

		if (empty($_POST['password'])) {
			$this->stackErrors['password'] = $message['password'];
		}
	}
}