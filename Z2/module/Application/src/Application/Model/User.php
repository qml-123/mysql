<?php
namespace Users\Model;
class User{
	public $num;
	public $password;

	public function __construct($data=null)
	{
	}

	public function exchangeArray($data)
	{
		$this->num = (isset($data['num']))? $data['num'] : null;
		$this->password = (isset($data['password']))?$data['password']:null;
	}
}
?>
