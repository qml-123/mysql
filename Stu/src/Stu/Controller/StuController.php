<?php
namespace Stu\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
use Application\Model\User;
use Application\Model\UserTable;
use Application\Model\NewsModel;

class StuController extends AbstractActionController
{
	protected $res;
	public function indexAction()
	{
		$viewModel=new ViewModel(array());
		$viewModel->setTemplate('stu/index/index.phtml');
		return $viewModel;
	}

	public function submitAction()
	{
		list($arr1,$arr2) = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('Stuvolunteer',$dbAdapter, null, $resultSetPrototype);
		$len = strlen($arr1);
		$stuvolunteer = new StuvolunteerTable($tableGateway);
		$error = "#" ;
		$res = "wating";
		$date = date('Y-m-d');
		session_start();
		$num = $_SESSION['num'];
		for($i = 0; $i < $len; $i++){
			$data=array(
				'stuid'=>$num,
				'cid'=>$arr1[$i],
				'tid'=>$arr2[$i],
				'vtime'=>$date,
				'result'=>$res
			);
			if($stuvolunteer->insert($data) == false){
				$error = '申请错误';
			}
		}
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		$sql = 'SELECT a.cid,a.cname,a.ctime,a.session,a.ctype,a.stunum,b.tuid,b.tname FROM Course as a,Raletionclass as b WHERE a.cid=b.cid';
		$this->res = $newModel->fetch($sql);

		$viewModel=new ViewModel(array('res'=>$this->res,'error'=>$error));
		$viewModel->setTemplate('stu/index/stuteer.phtml');
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
		$newModel = new NewsModel($dbAdapter);
		$sql = 'SELECT a.cid,a.cname,a.ctime,a.session,a.ctype,a.stunum,b.tuid,b.tname FROM Course as a,Raletionclass as b WHERE a.cid=b.cid';
		$this->res = $newModel->fetch($sql);

		$viewModel=new ViewModel(array('res'=>$this->res,'error'=>$error));
		$viewModel->setTemplate('stu/index/stuteer.phtml');
		return $viewModel;
	}

	public function loginAction()
	{
		return $this->redirect()->toRoute('application',array('controller'=>'Index','action'=>'index'));
	}

}

