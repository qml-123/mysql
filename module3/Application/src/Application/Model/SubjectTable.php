<?php
namespace Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Where;

class SubjectTable
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
	//根据学科名查
	public function fetchByName($name)
	{
		$result = $this->tableGateway->select(array('subname'=>$name));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//根据学科id
	public function fetchByid($id)
	{
		$result = $this->tableGateway->select(array('subid'=>$id));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	//不许更新
	public function insert($data)
	{
		$result = $this->tableGateway->select(array('subname'=>$data['sbuname']));
		if(!empty($result)) return false;
		else
		{
			$this->tableGateway->insert($data);
		}
		return true;
	}
	public function drop($data)
	{
		$result = $this->tableGateway->select(array('subname'=>$data['sbuname']));
		if(!empty($result))
		{
			$this->tableGateway->delete(array('subname'=>$data['sbuname']));
		}
		return true;
	}
}

?>

