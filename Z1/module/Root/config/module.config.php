<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Root\Controller\Root' => 'Root\Controller\RootController',
		),
	),
	'view_manager' => array(

		'template_path_stack' => array(
			'root' => __DIR__.'/../view',
		),
	),
	'router' => array(
		'routes' => array(
			'root' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/root',
					'defaults' => array(
						'__NAMESPACE__' => 'Root\Controller',
						'controller' => 'Root',
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



