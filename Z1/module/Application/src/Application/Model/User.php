<?php
namespace Application\Model;
class User{
	public $num;
	public $password;
	public $name;
	public $identity;

	public function setPassword($pas){
		$this->password = md5($pas);
	}

	public function exchangeArray($data)
	{
		$this->num = (isset($data['num']))?$data['num']:null;
		$this->name = (isset($data['name']))?$data['name']:null;
		if(isset($data['password'])){
			$this->setPassword($data['password']);
		}
		$this->identity = (isset($data['identity']))?$data['identity']:null;
	}

}
