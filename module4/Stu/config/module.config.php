<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Stu\Controller\Stu' => 'Stu\Controller\StuController',
		),
	),
	'view_manager' => array(

		'template_path_stack' => array(
			'stu' => __DIR__.'/../view',
		),
	),
	'router' => array(
		'routes' => array(
			'stu' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/stu',
					'defaults' => array(
						'__NAMESPACE__' => 'Stu\Controller',
						'controller' => 'Stu',
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



