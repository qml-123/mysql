<?php
namespace Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Where;

class CourseTable
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
	//根据课程id查询
	public function fetchByid($id)
	{
		$result = $this->tableGateway->select(array('cid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据课程名字查询
	public function fetchByname($name)
	{
		$result = $this->tableGateway->select(array('cname'=>$name));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据助教id查询
	public function fetchBytid($tid)
	{
		$result = $this->tableGateway->select(array('taid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//插入更新要求数据完整
	public function insert($data)
	{
		$result = $this->tableGateway->select(array('cname'=>$data['cname'],'ctime'=>$data['ctime']));
		if(!empty($result)) return false;
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
	public function updata($data)
	{
		$result = $this->tableGateway->select(array('cid'=>$data['cid']));
		if(!empty($result))
		{
			$this->tableGateway->update($data,array('cid'=>$data['cid']));
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
		if(!isset($data['cid']))
		{
			$result = $this->tableGateway->select(array('cid'=>$data['cid']));
			if(!empty($result))
			{
				$this->tableGateway->delete(array('cid'=>$data['cid']));
			}	
		}
		else
		{
			$result = $this->tableGateway->select(array('ctime'=>$data['ctime'],'cname'=>$data['cname']));
			if(!empty($result))
			{
				$this->tableGateway->delete(array('ctime'=>$data['ctime'],'cname'=>$data['cname']));
			}
		}
		return true;
	}

}

?>

