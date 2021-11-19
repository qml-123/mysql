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
		$viewModel=new ViewModel(array('error'=>false));
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
		$arr = array(
			'num'=>$_POST['num'],
			'password'=>filter_var($_POST['password'], FILTER_SANITIZE_STRING),
		);
		if(!ctype_digit($arr['num'])){
			$model=new ViewModel(array('error'=>true,));
			$model->setTemplate('application/index/index.phtml');
			return $model;
		}
		// echo $form->getData();
		//
		$f = $this->getUser($arr);

		// $f = $this->getUser($post);
		if($f  == "admin"){
			session_start();
			$_SESSION['num'] = $this->user['num'];
			$_SESSION['name'] = $this->user['name'];
			$_SESSION['password'] = $this->user['password'];
			$_SESSION['identity'] = $this->user['identity'];
			return $this->redirect()->toRoute('root/defaults',array('controller'=>'Root','action'=>'index'));
		}
		elseif($f == 'graduate_student'){
			session_start();
			$_SESSION['num'] = $this->user['num'];
			$_SESSION['name'] = $this->user['name'];
			$_SESSION['password'] = $this->user['password'];
			$_SESSION['identity'] = $this->user['identity'];
			return $this->redirect()->toRoute('stu/defaults',array('controller'=>'Stu','action'=>'index'));
		}
		elseif($f == 'teacher'){
			session_start();
			$_SESSION['num'] = $this->user['num'];
			$_SESSION['name'] = $this->user['name'];
			$_SESSION['password'] = $this->user['password'];
			$_SESSION['identity'] = $this->user['identity'];
			return $this->redirect()->toRoute('tea/defaults',array('controller'=>'Tea','action'=>'index'));
		}
		else{
			$model=new ViewModel(array('error'=>true,'pa'=>$arr['password']));
			$model->setTemplate('application/index/index.phtml');
			return $model;
		}
	}

	protected function getUser($data)
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('User',$dbAdapter, null, $resultSetPrototype);

		$userTable = new UserTable($tableGateway);

		$this->user=array(
			'num'=>$data['num'],
			'password'=>$data['password'],
		);
		$a = $userTable->getPassword($this->user);
		$this->user['name'] = $a['name'];
		$this->user['identity'] = $a['identity'];
		if(hash_equals(md5($this->user['password']),$a['password']))
			return $a['identity'];
		else
			return '#';
	}
}

