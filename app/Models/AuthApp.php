<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.10.2018
 * Time: 17:20
 */

namespace App\Models;

class AuthApp
{
	/**
	 * @var mixed|string
	 */
	private $clientId = '';

	/**
	 * @var mixed|string
	 */
	private $clientSecret = '';

	/**
	 * @var mixed|string
	 */
	private $description = '';

	/**
	 * @var int|mixed
	 */
	private $accessTTL = 0;

	/**
	 * @var int|mixed
	 */
	private $refreshTTL = 0;

	/**
	 * @var mixed|string
	 */
	private $created = '';

	/**
	 * @var mixed|string
	 */
	private $site = '';

	/**
	 * @var mixed|string
	 */
	private $type = '';

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @var array|mixed
	 */
	private $allowIps = [];

	/**
	 * AuthApp constructor.
	 * @param array $props
	 */
	public function __construct(array $props = [])
	{
		if (empty($props)) {
			return;
		}

		$this->clientId     = $props['clientId'];
		$this->type         = $props['type'];
		$this->clientSecret = $props['clientSecret'];
		$this->description  = $props['description'];
		$this->accessTTL    = $props['accessTTL'];
		$this->refreshTTL   = $props['refreshTTL'];
		$this->created      = $props['created'];
		$this->site         = $props['site'];
		$this->allowIps     = \json_decode($props['allowIps'], true);
	}

	/**
	 * @return string
	 */
	public function getClientId(): string
	{
		return $this->clientId;
	}

	/**
	 * @return string
	 */
	public function getClientSecret(): string
	{
		return $this->clientSecret;
	}

	/**
	 * @return array
	 */
	public function getAllowIps(): array
	{
		return $this->allowIps;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @return int
	 */
	public function getAccessTTL(): int
	{
		return $this->accessTTL;
	}

	/**
	 * @return int
	 */
	public function getRefreshTTL(): int
	{
		return $this->refreshTTL;
	}

	/**
	 * @return string
	 */
	public function getCreated(): string
	{
		return $this->created;
	}

	/**
	 * @return string
	 */
	public function getSite(): string
	{
		return $this->site;
	}
}