<?php
namespace Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Where;

class StuvolunteerTable
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
	//根据志愿id查询
	public function fetchByidx($id)
	{
		$result = $this->tableGateway->select(array('idx'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据学生id查询
	public function fetchBystuid($id)
	{
		$result = $this->tableGateway->select(array('stuid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据课程id查询
	public function fetchBytcid($tid)
	{
		$result = $this->tableGateway->select(array('cid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据老师id查询
	public function fetchBytid($tid)
	{
		$result = $this->tableGateway->select(array('tid'=>$tid));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据结果查询
	public function fetchByresult($result)
	{
		$result = $this->tableGateway->select(array('result'=>$result));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//插入更新要求数据完整
	public function insert($data)
	{
		$result = $this->tableGateway->select(array('stuid'=>$data['stuid'],'cid'=>$data['cid']));
		if(!empty($result)) return false;
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
	public function updata($data)
	{
		$result = $this->tableGateway->select(array('idx'=>$data['idx']));
		if(!empty($result))
		{
			$this->tableGateway->update($data,array('idx'=>$data['idx']));
		}
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
	//删除时data中可以只有id  设置了cid则cid删除 否则按name+time删除
	public function drop($data)
	{
		if(!isset($data['idx']))
		{
			$result = $this->tableGateway->select(array('idx'=>$data['idx']));
			if(!empty($result))
			{
				$this->tableGateway->delete(array('idx'=>$data['idx']));
			}	
		}
		else
		{
			$result = $this->tableGateway->select(array('stuid'=>$data['stuid'],'cid'=>$data['cid']));
			if(!empty($result))
			{
				$this->tableGateway->delete(array('stuid'=>$data['stuid'],'cid'=>$data['cid']));
			}
		}
		return true;
	}

}

?>

