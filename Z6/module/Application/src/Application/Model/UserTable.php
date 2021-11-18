<?php
namespace Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Where;

class UserTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}	
	
	public function getPassword($user)
	{
		// $this->tableGateway->insert($user);
		$num = $user['num'];
		$result = $this->tableGateway->select(array('num' => $num));	
		$row = $result->current();
		return array('password'=>$row['password'],'name'=>$row['name'],'identity'=>$row['identity']);
		// if($row['password'] == $user['password'])
			// return true;
		// else
			// return false;
	}

	
	public function fetchAll()
	{
		$result = $this->tableGateway->select();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}	



	public function fetchstu()
	{
		$result = $this->tableGateway->select(array('identity'=>'student'));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}

	public function saveUser($data)
	{
		$result = $this->tableGateway->select(array('num'=>$data['num']));
		$res = $result->current();
		if(empty($res)){
			$data['password'] = md5($data['password']);
			$this->tableGateway->insert($data);
			return true;
		}
		else{
			return false;
		}
	}
}

?>
