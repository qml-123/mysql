<?php
return array(
  'db' => array(
     'driver' => 'Pdo',
     'dsn' =>'mysql:dbname=education;host=121.40.40.117',
     'username' =>'root',
     'password' =>'qml6812015',
     'driver_options'=>array(
        PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \'UTF8\''
                             ),
              ),
     'service_manager'=>array(
        'factories'=>array(
           'Zend\Db\Adapter\Adapter'=>'Zend\Db\Adapter\AdapterServiceFactory',
                          )
                       ),
);
