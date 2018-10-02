<?php

return [
	[
		'name'       => 'api-get',
		'path'       => 'api/get',
		'controller' => 'Controller:Api:V1:ApiController',
		'action'     => 'get',
		'allow'      => ['GET'],
	],
	[
		'name'       => 'api-add',
		'path'       => 'api/add',
		'controller' => 'Controller:Api:V1:ApiController',
		'action'     => 'add',
		'allow'      => ['PUT'],
	],
	[
		'name'       => 'api-update',
		'path'       => 'api/update',
		'controller' => 'Controller:Api:V1:ApiController',
		'action'     => 'update',
		'allow'      => ['POST'],
	],
	[
		'name'       => 'api-delete',
		'path'       => 'api/delete',
		'controller' => 'Controller:Api:V1:ApiController',
		'action'     => 'delete',
		'allow'      => ['DELETE'],
	],
	[
		'name'       => 'search-word-api',
		'path'       => 'api/search-word',
		'controller' => 'Controller:Api:V1:ApiController',
		'action'     => 'searchWord',
		'allow'      => ['POST'],
	],
];