<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Tea\Controller\Tea' => 'Tea\Controller\TeaController',
		),
	),
	'view_manager' => array(

		'template_path_stack' => array(
			'tea' => __DIR__.'/../view',
		),
	),
	'router' => array(
		'routes' => array(
			'tea' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/tea',
					'defaults' => array(
						'__NAMESPACE__' => 'Tea\Controller',
						'controller' => 'Tea',
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



