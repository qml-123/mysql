<?php
namespace Users\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
USE Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Users\Model\Jwt;

class UsersController extends AbstractActionController
{
	public function getinfo($columns,$username = null, $password = null){
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('user')->columns($columns);

		if($username !== null)
			$select->where(array('username'=>$username, 'password'=>$password));

		$stmt = $sql->prepareStatementForSqlObject($select);

		$result = $stmt->execute();
		// $result = $tableGateway->select();

		$resultSet = new ResultSet();

		$res = $resultSet->initialize($result);

		$res = $res->toArray();
		return $res;
	}

	public function loginAction()
	{
		$request = $this->getRequest();
		if(!$request->isPost()){
			return new JsonModel();
		}

		$response = $this->getResponse();

		// $Cookie = $request->getCookie();
		$headers = apache_request_headers();
		// $authVal = $request->getHeaders('authorization')->getFieldValue();
		$authVal = $headers['Authorization'];


		$data = file_get_contents('php://input');
		$post = json_decode($data,true);
		$username = $post['username'];
		$password = $post['password'];
		$newpassword = ($password);


		if(!empty($authVal)){//处理token登录
			$getVerify = Jwt::verifyToken($authVal);
			if($getVerify == false){
				if(empty($username) || empty($password)){
					$response->setContent(json_encode(array('check'=>false)));
					return $response;
				}
			}
			else{
				$info = array(
					'username' => $getVerify['username'],
					'name' => $getVerify['name']
				);
				$response->setContent(json_encode(array('info' => $info,'check'=>true)));
				return $response;	
			}
		}

		$res = $this->getinfo(array('username','name','isAdmin'),$username, $password);
		$res = $res[0];
		if(empty($res)){
			$response->setStatusCode(422);
			$response->setContent(json_encode(array(
				'data'=>'用户名或密码错误',
				'error' => $authVal
			)));
			return $response;
		}
		$payload = array(
			'iss'=>'admin',
			'iat'=>time(),
			'exp'=>time()+7200,
			'nbf'=>time(),
			'sub'=>'www.admin.com',
			'jti'=>md5(uniqid('JWT').time()),
			'username' => $res['username'],
			'name' => $res['name'],
			'isAdmin' => $res['isAdmin']
		); 
		$userToken = Jwt::getToken($payload);
		$info = array(
			'username' => $res['username'],
			'name' => $res['name']
		);

		$response->setContent(json_encode(array('userToken'=>$userToken, 'info' => $info)));
		return $response;	

	}


	// public function registerAction()
	// {
	//
	// }

	public function indexAction()
	{
		$request = $this->getRequest();

		// $Cookie = $request->getCookie();
		// $authVal = $Cookie['userToken'];
		$authVal = $request->getHeaders('authorization')->getFieldValue();

		$getVerify = Jwt::verifyToken($authVal);

		$response = $this->getResponse();
		if($getVerify == false || $getVerify['isAdmin'] !== '1'){
			$response->setStatusCode(422);
			$response->setContent(json_encode(array('data'=>'无权限')));
			return $response;
		}

		$res = $this->getinfo(array('username','name'));

		$response->setContent(json_encode(array('data'=>$res)));
		return $response;
	}
}
