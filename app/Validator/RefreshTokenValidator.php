<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.2018
 * Time: 14:40
 */

namespace App\Validator;

use Helper\Util;
use System\Validators\AbstractValidator;
use System\Validators\Validators;

class RefreshTokenValidator extends AbstractValidator
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
	}
}