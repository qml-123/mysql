<?php
namespace Root\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Root\Form\RootForm;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
use Application\Model\User;
use Application\Model\UserTable;

class RootController extends AbstractActionController
{
	protected $res;
	public function indexAction()
	{
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
	


	public function info()
	{
		$view = new ViewModel();
		$view->setTemplate('root/manage/info.phtml');
		return $view;
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

