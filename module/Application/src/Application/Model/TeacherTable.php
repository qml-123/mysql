<?php
namespace Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Where;

class TeacherTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$result = $this->tableGateway->select();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据学科查询
	public function fetchBySub($sub)
	{
		$result = $this->tableGateway->select(array('subid'=>$sub));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据老师学号查询
	public function fetchByid($id)
	{
		$result = $this->tableGateway->select(array('uid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}	
	public function insert($data)
	{
		$result = $this->tableGateway->select(array('uid'=>$data['uid']));
		if(!empty($result)) return false;
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
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
	public function drop($data)
	{
		$result = $this->tableGateway->select(array('uid'=>$data['uid']));
		if(!empty($result))
		{
			$this->tableGateway->delete(array('uid'=>$data['uid']));
		}
		return true;
	}

}

?>

