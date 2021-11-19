<?php
namespace Tea\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
use Application\Model\User;
use Application\Model\UserTable;
use Application\Model\NewsModel;

class TeaController extends AbstractActionController
{
	public function indexAction()
	{
		$viewModel=new ViewModel();
		$viewModel->setTemplate('tea/index/index.phtml');
		return $viewModel;
	}

	public function submitAction()
	{

		$arr = json_decode(file_get_contents('php://input'), true);

		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$tid = $_SESSION['num'];
		$cid = $arr[0];
		$stuid = $arr[1];
		$result = $arr[2];
		$sql = 'update Stuvolunteer set result="'.$result.'" where cid="'.$cid.'" and stuid="'.$stuid.'" and tid="'.$tid.'"';
		$newModel->exec($sql);
		$sql = 'insert into Raletionta(cid,taid,tuid) values("'.$cid.'","'.$stuid.'","'.$tid.'")';
		$newModel->exec($sql);
		$sql = 'update Course set taid = "'.$stuid.'" where cid = "'.$cid.'"';
		$newModel->exec($sql);
		$sql = 'select a.cid,b.cname,a.vtime,b.session,a.stuid,c.name,a.result from Stuvolunteer as a,Course as b, Student as c where a.cid=b.cid and a.stuid=c.uid and a.result="wating"';
		$res = $newModel->fetch($sql);
		$viewModel = new ViewModel(array('res'=>$res));
		$viewModel->setTemplate('tea/index/stuteer.phtml');
		return $viewModel;

	}

	public function infoAction()
	{
		$view = new ViewModel();
		$view->setTemplate('tea/index/info.phtml');
		return $view;
	}

	public function solveAction() //申请处理
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		$sql = 'select a.cid,b.cname,a.vtime,b.session,a.stuid,c.name,a.result from Stuvolunteer as a,Course as b, Student as c where a.cid=b.cid and a.stuid=c.uid and a.result="wating"';
		$res = $newModel->fetch($sql);
		$viewModel = new ViewModel(array('res'=>$res));
		$viewModel->setTemplate('tea/index/stuteer.phtml');
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
		$tid = $_SESSION['num'];
		$sql = "select a.cid,b.cname,b.ctime,b.session,b.taid,d.name from Raletionclass as a,Course as b, Stuvolunteer as c,Student as d where a.tuid='".$tid."' and a.cid=b.cid and a.cid=c.cid and a.tuid=c.tid
and b.taid=c.stuid and b.taid=d.uid and c.result='allow'";
		$acc = $newModel->fetch($sql);

		$sql = "select a.cid,b.cname,b.ctime,b.session,b.taid,d.name from Raletionclass as a,Course as b, Stuvolunteer as c,Student as d where a.tuid='".$tid."' and a.cid=b.cid and a.cid=c.cid and a.tuid=c.tid
and b.taid=c.stuid and b.taid=d.uid and c.result='refuse'";

		$rej = $newModel->fetch($sql);
		$viewModel = new ViewModel(array('acc'=>$acc,'rej'=>$rej));
		$viewModel->setTemplate('tea/index/passed.phtml');
		return $viewModel;
	}

}

