<?php
namespace Application\Form;
use Zend\Form\Form;
class LoginForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('Root');
		$this->setAttribute('method','post');
		$this->setAttribute('enctype','multipart/form-data');
		$this->add(array('name'=>'submit','attributes'=>array('type'=>'Submit','value'=>'学籍管理',), ));
	}
}

?>
