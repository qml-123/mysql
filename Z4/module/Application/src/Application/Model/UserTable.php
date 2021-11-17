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
		if($row['password'] == $user['password'])
			return true;
		else
			return false;
	}

	public function fetchstu()
	{
		$result = $this->tableGateway->select(array('identity'=>'student'));
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
}

?>
