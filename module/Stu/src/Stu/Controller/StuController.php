<?php
namespace Stu\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
use Application\Model\User;
use Application\Model\UserTable;
use Application\Model\NewsModel;
use Application\Model\Stuvolunteer;
// use Application\Model\

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

		$arr = json_decode(file_get_contents('php://input'), true);
		// $r = '$';
		// if(empty($arr))
			// $r = '##';
		// $viewModel=new ViewModel(array('res'=>$this->res,'error'=>$r,'arr'=>$arr));
		// $viewModel->setTemplate('stu/index/stuteer.phtml');
		// return $viewModel;
		// $sm = $this->getServiceLocator();
		// $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		// $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		// $tableGateway = new \Zend\Db\TableGateway\TableGateway('Stuvolunteer',$dbAdapter, null, $resultSetPrototype);
		// $stuvolunteer = new Stuvolunteer($tableGateway);
		$error = "#";
		$res = "wating";
		$da = date('Y-m-d');
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$num = $_SESSION['num'];

		foreach($arr as $ar){
			$data=array(
				'stuid'=>$num,
				'cid'=>$ar['cid'],
				'tid'=>$ar['tid'],
				'vtime'=>$da,
				'result'=>$res
			);
			$f = $newModel->insert('Stuvolunteer',$data);
			if($f == 0){
				$error = '申请错误';
			}
		}
		// $sm = $this->getServiceLocator();
		// $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		// $newModel = new NewsModel($dbAdapter);
		$sql = 'SELECT a.cid,a.cname,a.ctime,a.session,a.ctype,a.stunum,b.tuid,b.tname FROM Course as a,Raletionclass as b WHERE a.cid=b.cid and a.taid is null';
		$this->res = $newModel->fetch($sql);

		$viewModel=new ViewModel(array('res'=>$this->res,'error'=>$error));
		$viewModel->setTemplate('stu/index/stuteer.phtml');
		return $viewModel;

	}

	public function infoAction()
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
		$sql = 'SELECT a.cid,a.cname,a.ctime,a.session,a.ctype,a.stunum,b.tuid,b.tname FROM Course as a,Raletionclass as b WHERE a.cid=b.cid and a.taid is null';
		$this->res = $newModel->fetch($sql);

		$viewModel=new ViewModel(array('res'=>$this->res,'error'=>'#'));
		$viewModel->setTemplate('stu/index/stuteer.phtml');
		return $viewModel;
	}

	public function loginAction()
	{
		return $this->redirect()->toRoute('application',array('controller'=>'Index','action'=>'index'));
	}
	public function showAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$num = $_SESSION['num'];
		$sql1 = 'SELECT a.cid,b.cname,b.ctime,b.session,a.tid,c.tname FROM Stuvolunteer as a,Course as b, Raletionclass as c WHERE a.cid=b.cid and a.tid=c.tuid and a.result="wating" and a.stuid="'.$num.'" and b.cid=c.cid';
		$res1 = $newModel->fetch($sql1);

		$sql2 = 'SELECT a.cid,b.cname,b.ctime,b.session,a.tid,c.tname FROM Stuvolunteer as a,Course as b, Raletionclass as c WHERE a.cid=b.cid and a.tid=c.tuid and a.result="allow" and a.stuid="'.$num.'" and b.cid=c.cid';
		$res2 = $newModel->fetch($sql2);

		$sql3 = 'SELECT a.cid,b.cname,b.ctime,b.session,a.tid,c.tname FROM Stuvolunteer as a,Course as b, Raletionclass as c WHERE a.cid=b.cid and a.tid=c.tuid and a.result="refuse" and a.stuid="'.$num.'" and b.cid=c.cid';
		$res3 = $newModel->fetch($sql3);

		$viewModel=new ViewModel(array('res1'=>$res1,'res2'=>$res2,'res3'=>$res3,'error'=>'#'));
		$viewModel->setTemplate('stu/index/passed.phtml');
		return $viewModel;
	}
}

