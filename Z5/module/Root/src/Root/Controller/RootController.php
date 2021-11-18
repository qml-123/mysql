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
			$viewModel=new ViewModel();
		$viewModel->setTemplate('root/manage/index.phtml');
		return $viewModel;

	}
	

	public function addAction()
	{
		$arr=array();
		$arr['num'] = $_POST['num'];
		$arr['name'] = $_POST['name'];
		$arr['password'] = '000000';
		$arr['identity'] = $_POST['identity'];
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('user',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$this->res = $userTable->fetchstu();

		if(strlen($arr['num']) !== 6){
			$view = new ViewModel(array('res' => $this->res,'error'=>'请输入6位数字'));
			$view->setTemplate("root/manage/student.phtml");	
			return $view;
		}
		if(!$userTable->saveUser($arr)){
			$view = new ViewModel(array('res' => $this->res,'error'=>'用户已存在'));
			$view->setTemplate("root/manage/student.phtml");	
			return $view;
		}
		$view = new ViewModel(array('res' => $this->res,'error'=>'#'));
		$view->setTemplate("root/manage/student.phtml");	
		return $view;
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
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('user',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$this->res = $userTable->fetchstu();
		$view = new ViewModel(array('res' => $this->res,'error'=>'#'));
		$view->setTemplate("root/manage/student.phtml");	
		return $view;
	}

	public function loginAction()
	{
		$form = new LoginForm();
		$view = new ViewModel(array('form'=>$form));
		$view->setTemplate('application/index/index.phtml');
		return $view;
	}

}

