<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Реестр невзысканных сумм по НП',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),               
    
    'theme'=>'bootstrap',
    
    'language'=>'ru',
    
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'111',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
		    'ipFilters'=>array('127.0.0.1','::1','10.186.201.34'),
            'generatorPaths'=>array(
                'bootstrap.gii',
            ),
		),		
	),
    
    
    
    
	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),                        
        
        'bootstrap'=>array(
            'class'=>'ext.bootstrap.components.Bootstrap',            
        ),
                        
        
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
            'urlSuffix'=>'.html',
            //'showScriptName'=>false,
		),
        
		'db'=>require(__DIR__ . '/db.php'),		
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
                /*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
        
        
        'browser' => array(
            'class' => 'application.extensions.browser.CBrowserComponent',
        ),
        
        'audit' => array(
            'table' => '{{audit}}',
            'class' => 'application.extensions.audit.CAuditComponent',
        ),

        'Ldap'=>array(
            'class'=>'LdapComponent',
            'baseDN'=>'DC=regions,DC=tax,DC=nalog,DC=ru',
            'accountSuffix'=>'@regions.tax.nalog.ru',
            'domainControllers'=>array('regions.tax.nalog.ru'),
            'adminUsername'=>'',
            'adminPassword'=>'',
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
        'ldap'=>array(
            'host'=>'regions.tax.nalog.ru',
            'ou'=>'Users',
            'dc'=>array('regions','tax','nalog','ru'),
        ),
	),
);