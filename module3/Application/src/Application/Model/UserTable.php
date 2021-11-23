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
		$result = $this->tableGateway->select(array('uid' => $num));	
		$row = $result->current();
		return array('password'=>$row['upsd'],'name'=>$row['uname'],'identity'=>$row['utype']);
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
		$result = $this->tableGateway->select(array('utype'=>'student'));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}

	public function saveUser($data)
	{
		$result = $this->tableGateway->select(array('uid'=>$data['uid']));
		$res = $result->current();
		if(empty($res)){
			$data['upsd'] = md5($data['upsd']);
			$this->tableGateway->insert($data);
			return true;
		}
		else{
			return false;
		}
	}

//根据用户类型查询
	public function fetchByType($type)
	{
		$result = $this->tableGateway->select(array('utype'=>$type));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据id查询
	public function fetchByid($id)
	{
		$result = $this->tableGateway->select(array('uid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//
	public function updata($data)
	{
		$result = $this->tableGateway->select(array('uid'=>$data['uid']));
		if(!empty($result))
		{
			$this->tableGateway->update($data,array('uid'=>$data['uid']));
		}
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
	//删除时data中可以只有id
	public function drop($data)
	{
		$result = $this->tableGateway->select(array('uid'=>$data));
		if(!empty($result))
		{
			$this->tableGateway->delete(array('uid'=>$data));
		}
		return true;
	}
}
?>
