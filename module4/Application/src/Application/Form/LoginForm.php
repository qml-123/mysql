<?php
namespace Application\Form;
use Zend\Form\Form;
class LoginForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('Login');
		$this->setAttribute('method','post');
		$this->setAttribute('enctype','multipart/form-data');
		$this->add(array('name'=>'num','attributes'=>array('type'=>'Text',),'options'=>array('label'=>'ID Number',),));

		$this->add(array('name'=>'password','attributes'=>array('type'=>'Password',), 'options'=>array('label'=>'password',),));
		$this->add(array('name'=>'submit','attributes'=>array('type'=>'Submit','value'=>'login',), ));
	}
}

?>
