<?php

return [
	[
		'name'       => 'auth',
		'path'       => 'oauth/access-token',
		'controller' => 'Controller:AuthController',
		'action'     => 'accessToken',
		'allow'      => ['POST'],
	],
	[
		'name'       => 'refresh',
		'path'       => 'oauth/refresh-token',
		'controller' => 'Controller:AuthController',
		'action'     => 'refreshToken',
		'allow'      => ['POST'],
	],
	[
		'name'       => 'logout',
		'path'       => 'oauth/logout',
		'controller' => 'Controller:LogoutController',
		'action'     => 'logout',
		'allow'      => ['POST'],
	],
	[
		'name'       => 'logoutAllGadget',
		'path'       => 'oauth/logout-all',
		'controller' => 'Controller:LogoutController',
		'action'     => 'logoutAllGadget',
		'allow'      => ['POST'],
	],
];