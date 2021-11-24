<?php
namespace Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class NewsModel {
	protected $adapter;

	public function __construct($dbAdapter=null)
	{
		if($dbAdapter==null)
			$this->adapter = new Adapter(array(
				'driver'=>'Pdo_Mysql',
				'database'=>'Manager',
				'hostname'=>'121.40.40.117',
				'username'=>'root',
				'password'=>'qml6812015'
			));
		else
			$this->adapter = $dbAdapter;
	}
	//用作SQL语句更新,删除和插入的接口
	public function exec($sql)
	{
		$stmt = $this->adapter->createStatement($sql);
		$stmt->prepare();
		$stmt->execute();
	}

	//查找
	public function fetch($sql)
	{
		$stmt = $this->adapter->createStatement($sql);
		$stmt->prepare();
		$result = $stmt->execute();
		$resultset = new ResultSet;
		$resultset->initialize($result);
		$rows = array();
		$rows = $resultset->toArray();
		return $rows;
	}
	//
	//插入
	public function insert($table,$data)
	{
		$sql = new Sql($this->adapter);
		$insert=$sql->insert($table);
		$insert->values($data);
		return $sql->prepareStatementForSqlObject($insert)->execute()->getAffectedRows();
	}

	//更新
	public function update($table,$data,$where)
	{
		$sql = new Sql($this->adapter);
		$update=$sql->update($table);
		$update->set($data);
		$update->where($where);
		return $sql->prepareStatementForSqlObject($update)->execute()->getAffectedRows();
	}

	//删除
	public function delete($table,$where)
	{
		$sql = new Sql($this->adapter);
		$delete = $sql->delete($table)->where($where);
		return $sql->prepareStatementForSqlObject($delete)->execute()->getAffectedRows();
	}

	//返回最后插入的主键值
	public function lastInsertId()
	{
		return $this->adapter->getDriver()->getLastGeneratedValue();
	}  

}
?>
