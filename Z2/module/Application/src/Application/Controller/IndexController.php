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
	public $user = array();
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
		$post=$this->request->getPost();
		$form=new LoginForm();
		$inputFilter = new LoginFilter();
		$form->setInputFilter($inputFilter);
		$form->setData($post);
		if(!$form->isValid()){
			$model=new ViewModel(array('error'=>true, 'form'=>$form,));
			$model->setTemplate('application/index/index.phtml');
			return $model;
		}
		// echo $form->getData();
		//
		$f = $this->getUser($form->getData());
		// $f = $this->getUser($post);
		if($f  == true){
			$model=new ViewModel();
			$model->setTemplate('application/index/success.phtml');
			return $model;
		}
		else{
			$model=new ViewModel(array('error'=>true, 'form'=>$form,));
			$model->setTemplate('application/index/index.phtml');
			return $model;
		}
	}

	protected function getUser(array $data)
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
		return $userTable->getPassword($this->user);
	}
}
