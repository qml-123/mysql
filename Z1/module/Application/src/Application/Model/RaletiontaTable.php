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
	//根据助教id查询
	public function fetchBytaid($id)
	{
		$result = $this->tableGateway->select(array('taid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据课程id查询
	public function fetchBycid($cid)
	{
		$result = $this->tableGateway->select(array('cid'=>$cid));
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
	//插入更新要求数据完整
	public function insert($data)
	{
		$result = $this->tableGateway->select(array('cid'=>$data['cid'],'taid'=>$data['taid']));
		if(!empty($result)) return false;
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
	public function updata($data)
	{
		$result = $this->tableGateway->select(array('cid'=>$data['cid'],'taid'=>$data['taid']));
		if(!empty($result))
		{
			$this->tableGateway->update($data,array('cid'=>$data['cid'],'taid'=>$data['taid']));
		}
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
	//删除时data中可以只有主键
	public function drop($data)
	{
		$result = $this->tableGateway->select(array('cid'=>$data['cid'],'taid'=>$data['taid']));
		if(!empty($result))
		{
			$this->tableGateway->delete(array('cid'=>$data['cid'],'taid'=>$data['taid']));
		}
		return true;
	}

}

?>

