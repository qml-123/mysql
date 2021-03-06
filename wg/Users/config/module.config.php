<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Users\Controller\Users' => 'Users\Controller\UsersController',
		),
	),
	'view_manager' => array(

		'template_path_stack' => array(
			'users' => __DIR__.'/../view',
		),
	),
	'router' => array(
		'routes' => array(
			'users' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/users',
					'defaults' => array(
						'__NAMESPACE__' => 'Users\Controller',
						'controller' => 'Users',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'defaults' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:controller[/:action]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
			),
		),
	),

);



