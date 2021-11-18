<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Form\LoginFilter;
use Application\Model\User;
use Application\Model\UserTable;

class IndexController extends AbstractActionController
{
	public function indexAction()
	{
		$form=new LoginForm();
		$viewModel=new ViewModel(array('form'=>$form));
		return $viewModel;
	}


	public function successAction()
	{
		$view = new ViewModel();
		$view->setTemplate('application/index/success.phtml');
		return $view;
	}

	public function processAction()
	{
		if(!$this->request->isPost()){
			$form=new LoginForm();
			$viewModel=new ViewModel(array('form'=>$form));
			return $viewModel;
		}
		$arr = array(
			'num'=>$_POST['num'];
			'password'=>$_POST['possword'];
		);

		if(!$form->isValid()){
			$model=new ViewModel(array('error'=>true,));
			$model->setTemplate('application/index/index.phtml');
			return $model;
		}
		// echo $form->getData();
		//
		$f = $this->getUser($arr);
		// $f = $this->getUser($post);
		if($f  == "root"){
			session_start();
			$_SESSION['num'] = $this->user['num'];
			$_SESSION['name'] = $this->user['name'];
			$_SESSION['password'] = $this->user['password'];
			$_SESSION['identity'] = $this->user['identity'];
			$model=new ViewModel();
			$model->setTemplate('root/manage/index.phtml');
			return $model;
		}
		else{
			$model=new ViewModel(array('error'=>true, 'form'=>$form,));
			$model->setTemplate('application/index/index.phtml');
			return $model;
		}
	}

	protected function getUser($data)
	{

		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('user',$dbAdapter, null, $resultSetPrototype);

		$userTable = new UserTable($tableGateway);

		$this->user=array(
			'num'=>$data['num'],
			'password'=>$data['password'],
		);
		$arr = $userTable->getPassword($this->user);
		$this->user['name'] = $arr['name'];
		return $arr['identity'];
	}
}

