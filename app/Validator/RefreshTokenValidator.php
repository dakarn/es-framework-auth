<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.2018
 * Time: 14:40
 */

namespace ES\App\Validator;

use ES\Kernel\Helper\Util;
use ES\Kernel\Validators\AbstractValidator;
use ES\Kernel\Validators\Validators;

class RefreshTokenValidator extends AbstractValidator
{

	/**
	 * @var bool
	 */
	public $isUseFlashErrors = false;

	/**
	 * @throws \ES\Kernel\Exception\FileException
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