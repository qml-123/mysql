<?php
namespace Application\Form;
use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array('name'=>'num','required'=>true,'filters'=>array(array('name'=>'StripTags',),),
			'validators'=>array(array('name'=>'StringLength','options'=>array('encoding'=>'UTF-8','min'=>6,'max'=>6,),),),));
		$this->add(array('name'=>'password','required'=>true,));
	}

}

?>
