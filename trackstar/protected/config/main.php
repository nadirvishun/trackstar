<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'TrackStar',
// 	'homeUrl'=>'project',
	'homeUrl'=>'http://localhost/git/yii_1/trackstar/project',
	
	'theme'=>'new',
	'language'=>'en_us',	
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.admin.models.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'admin',
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
		//缓存管理
		'cache'=>array(
			'class'=>'system.caching.CFileCache',
		),
		// uncomment the following to enable URLs in path-format
		//URL路由管理
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
				
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
// 				'' => 'site/index',
				'<pid:\d+>/commentfeed'=>array('site/commentfeed', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
     		    'commentfeed'=>array('site/commentfeed', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),

			),
		),
		
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		
		// uncomment the following to use a MySQL database
		//MYSQL数据库管理
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=trackstar_dev',
			'emulatePrepare' => true,
			'username' => 'vishun',
			'password' => '123456',
			'charset' => 'utf8',
		),
		//错误信息管理
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		//日志管理
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error',
				),
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'info,trace',
					'logFile'=>'infoMessages.log',
				),
// 				array(
// 					'class'=>'CWebLogRoute',
// 					'levels'=>'warning',
// 				)
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		/**
		 *权限认证管理器
		 */
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
		)
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);