<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.10.2018
 * Time: 17:20
 */

namespace App\Models;

use System\Database\DB;
use System\Validators\AbstractValidator;

class AuthAppRepository
{
	/**
	 * @var bool
	 */
	private $isLoaded = false;

	/**
	 * @var AuthApp
	 */
	private $result;

	/**
	 * @param AbstractValidator $validator
	 * @return AuthApp
	 * @throws \Exception
	 */
	public function loadClientApp(AbstractValidator $validator): AuthApp
	{
		$result = DB::MySQLAdapter()->fetchRow('
			SELECT * 
			FROM 
				access_application
			WHERE 
				`clientId` = "' . $validator->getValueField('clientId') . '"
				AND 
				`clientSecret` = "' . $validator->getValueField('clientSecret') . '"
			LIMIT 1
		');

		if (!empty($result)) {
			if ($result['site'] !== $validator->getValueField('site')) {
				return new AuthApp();
			}

			$this->isLoaded = true;
		}

		$this->result = new AuthApp($result);

		return $this->result;
	}

	/**
	 * @return AuthApp
	 */
	public function getResult(): AuthApp
	{
		return $this->result;
	}

	/**
	 * @return bool
	 */
	public function isLoaded(): bool
	{
		return $this->isLoaded;
	}
}