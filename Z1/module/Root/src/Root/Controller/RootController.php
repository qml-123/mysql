<?php
namespace Root\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Root\Form\RootForm;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
use Application\Model\User;
use Application\Model\UserTable;
use Application\Model\NewsModel;

class RootController extends AbstractActionController
{
	protected $res;
	public function indexAction()
	{
		session_start();
		if($_SESSION['identity'] !== 'admin'){
			$viewModel=new ViewModel();
			$viewModel->setTemplate('root/error/404.phtml');
			return $viewModel;
		}
		$viewModel=new ViewModel();
		$viewModel->setTemplate('root/manage/index.phtml');
		return $viewModel;
	}

	public function deleteAction()
	{
		$arr = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('User',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		
		foreach($arr as $uid){
			$userTable->drop($uid);	
		}
		$this->res = $userTable->fetchAll();
		$view = new ViewModel(array('res' => $this->res,'error'=>'#'));
		$view->setTemplate("root/manage/student.phtml");	
		return $view;
	}
	

	public function newpassAction()
	{
		if(isset($_POST['Submit'])){
			$error = '#';
			session_start();
			$old = $_SESSION['password'];
			$oldpost = filter_var($_POST['oldpass'], FILTER_SANITIZE_STRING);
			$newpass1 = filter_var($_POST['newpass1'], FILTER_SANITIZE_STRING);
			$newpass2 = filter_var($_POST['newpass2'], FILTER_SANITIZE_STRING);


			if($old !== $oldpost || $newpass1 !== $newpass2 || empty($newpass1))
			{
				$view = new ViewModel(array('error'=>'更改错误'));
				$view->setTemplate('root/manage/info.phtml');
			}
			session_start();
			$_SESSION['password'] = $newpass1;
			$num = $_SESSION['num'];
			$identity = $_SESSION['identity'];
			$name = $_SESSION['name'];
			$data=array(
				'uid'=>$num,
				'upsd'=>md5($newpass1),
				'utype'=>$identity,
				'uname'=>$name
			);
			$sm = $this->getServiceLocator();
			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
			$tableGateway = new \Zend\Db\TableGateway\TableGateway('User',$dbAdapter, null, $resultSetPrototype);
			$userTable = new UserTable($tableGateway);
			$userTable->updata($data);	
		}
		return $this->redirect()->toRoute('root/defaults',array('controller'=>'Root','action'=>'info'));
	}

	public function infoAction()
	{
		$view = new ViewModel(array('error'=>'#'));
		$view->setTemplate('root/manage/info.phtml');
		return $view;
	}

	// public function taAction(){
		// $sm = $this->getServiceLocator();
		// $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		// $newModel = new NewsModel($dbAdapter);
		// $
//
	// }

	public function subAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('Subject',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$this->res = $userTable->fetchAll();
		$view = new ViewModel(array('res'=>$this->res));
		$view->setTemplate('root/manage/sub.phtml');
		return $view;
	}
	public function addsubAction()
	{
		$arr=array(
			'subid'=>$_POST['id'],
			'subname'=>$_POST['name']
		);

		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('Subject',$dbAdapter, null, $resultSetPrototype);

		$tableGateway->insert($arr);

		return $this->redirect()->toRoute('root/defaults',array('controller'=>'Root','action'=>'sub'));
	}

	public function addAction()
	{
		$arr=array();
		$arr['uid'] = $_POST['num'];
		$arr['upsd'] = '000000';
		$arr['utype'] = $_POST['identity'];
		$arr['uname'] = $_POST['name'];
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('User',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$this->res = $userTable->fetchAll();


		if(isset($_POST['Submit'])){
			if(strlen($arr['uid']) !== 6 or !ctype_digit($arr['uid'])){
				$view = new ViewModel(array('res' => $this->res,'error'=>'请输入6位数字'));
				$view->setTemplate("root/manage/student.phtml");	
				return $view;
			}
			if(!$userTable->saveUser($arr)){
				$view = new ViewModel(array('res' => $this->res,'error'=>'用户已存在'));
				$view->setTemplate("root/manage/student.phtml");	
				return $view;
			}

			$this->res = $userTable->fetchAll();
			$view = new ViewModel(array('res' => $this->res,'error'=>'#'));
			$view->setTemplate("root/manage/student.phtml");	
			return $view;
		}
		else{
			$view = new ViewModel(array('res' => $this->res,'error'=>'#'));
			$view->setTemplate("root/manage/student.phtml");	
			return $view;
		}
	}

	public function jumpaddAction()
	{
		$viewModel=new ViewModel();
		$viewModel->setTemplate('root/manage/addUser.phtml');
		return $viewModel;

	}

	public function jumpAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('User',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$this->res = $userTable->fetchAll();
		$view = new ViewModel(array('res' => $this->res,'error'=>'#'));
		$view->setTemplate("root/manage/student.phtml");	
		return $view;
	}

	public function loginAction()
	{
		return $this->redirect()->toRoute('application',array('controller'=>'Index','action'=>'index'));
	}

}

