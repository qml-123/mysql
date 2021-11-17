<?php
namespace Root\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Root\Form\RootForm;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
// use Root\Model\User;
use Application\Model\UserTable;

class RootController extends AbstractActionController
{

	public function indexAction()
	{
		$viewModel=new ViewModel();
		$viewModel->setTemplate('root/manage/index.phtml');
		return $viewModel;
	}

	public function jumpAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('user',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$res = $userTable->fetchstu();
		$view = new ViewModel(array('res' => $res));
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

