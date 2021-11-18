<?php
namespace Stu\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
use Application\Model\User;
use Application\Model\UserTable;

class StuController extends AbstractActionController
{
	protected $res;
	public function indexAction()
	{
		$viewModel=new ViewModel();
		$viewModel->setTemplate('stu/index/index.phtml');
		return $viewModel;
	}

	public function deleteAction()
	{
			$viewModel=new ViewModel();
		$viewModel->setTemplate('root/manage/index.phtml');
		return $viewModel;

	}
	


	public function info()
	{
		$view = new ViewModel();
		$view->setTemplate('stu/index/info.phtml');
		return $view;
	}

	public function jumpAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('user',$dbAdapter, null, $resultSetPrototype);
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

