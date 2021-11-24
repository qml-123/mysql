<?php
namespace Tea\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
// use Root\Form\LoginFilter;
use Application\Model\User;
use Application\Model\StudentTable;
use Application\Model\UserTable;
use Application\Model\NewsModel;
use Stu\Model\Works;

class TeaController extends AbstractActionController
{
	public function indexAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$num = $_SESSION['num'];
		$sql = "select * from Teacher where uid='".$num."'";
		$arr = $newModel->fetch($sql);
		$sub = $arr[0]['resid'];
		$viewModel=new ViewModel(array('error'=>'#','sub'=>$sub));
		$viewModel->setTemplate('tea/index/index.phtml');
		return $viewModel;
	}

	public function smanAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$num = $_SESSION['num'];
		$arr = $newModel->fetch('select * from Student where tuid="'.$num.'"');
		$res = $newModel->fetch('select * from Subject where subid="'.$arr['subid'].'"');
		$arr['subid'] = $res[0]['subname'];
		$viewModel=new ViewModel(array('error'=>'#','res'=>$arr));
		$viewModel->setTemplate('tea/index/sman.phtml');
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
		$arr = $newModel->fetch("select tanum from Subject where tuiid='".$stuid."'");
		$j = (int)$arr[0]['tuiid'];

		$viewModel = new ViewModel(array('res'=>$res));
		$viewModel->setTemplate('tea/index/stuteer.phtml');
		return $viewModel;

	}

	public function tacheckAction(){
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$uid = $_SESSION['num'];
		$sql = "select a.cid,b.cname,b.ctime,b.session,a.taid,c.name,a.result,a.workReport from Raletionta as a,Course as b,Student as c where a.cid=b.cid and a.taid=b.taid and a.taid=c.uid and a.result='wating' and a.tuid='".$uid."'";
		$res = $newModel->fetch($sql);
		$viewModel = new ViewModel(array('res'=>$res));
		$viewModel->setTemplate('tea/index/tacheck.phtml');
		return $viewModel;
	}

	public function acsubmitAction(){
		$arr = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		$acname = $arr[0];
		$stuid = $arr[1];
		$result = $arr[2];

		$sql = "update Accom set result='".$result."' where comname='".$acname."' and stuid='".$stuid."'";
		$newModel->exec($sql);

		return $this->redirect()->toRoute('tea/defaults',array('controller'=>'Tea','action'=>'academic'));
	}

	public function prosubmitAction()
	{
		$arr = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$tid = $_SESSION['num'];
		$stuid = $arr[0];
		$ptype = $arr[1];
		$pname = $arr[2];
		$result = $arr[3];
		$sql = "update Project set result='".$result."' where tid='".$stuid."' and ptype='".$ptype."' and pname='".$pname."'";
		$newModel->exec($sql);

		return $this->redirect()->toRoute('tea/defaults',array('controller'=>'Tea','action'=>'project'));
	}

	public function scouAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$num = $_SESSION['num'];
		$arr = $newModel->fetch('select a.cid,a.cname,a.ctime,a.session,a.ctype,a.taid,b.name,a.stunum from Course as a,Student as b,Raletionclass as c
		  	where a.cid=c.cid and c.tuid="'.$num.'" and a.taid=b.uid');
		$viewModel=new ViewModel(array('error'=>'#','res'=>$arr));
		$viewModel->setTemplate('tea/index/scou.phtml');
		return $viewModel;
	}

	public function ssubAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);

		session_start();
		$num = $_SESSION['num'];
		$ret = $newModel->fetch("select resid from Teacher where uid='".$num."'");
		$sid = $ret[0]['resid'];
		$sql = 'select a.uid,a.name,a.title,b.subname from Teacher as a,Subject as b where a.subid=b.subid and a.subid="'.$sid.'"';
		$arr2 = $newModel->fetch($sql);
		$sql = 'select a.uid,a.birday,a.name,a.sex,a.enrollday,b.subname,a.tanum from Student as a,Subject as b where b.subid=a.subid and 
			b.subid="'.$sid.'"';
		$arr = $newModel->fetch($sql);
		$viewModel=new ViewModel(array('error'=>'#','res'=>$arr,'res2'=>$arr2));
		$viewModel->setTemplate('tea/index/ssub.phtml');
		return $viewModel;
	}

	public function workssubmitAction(){
		$arr = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		$stuid = $arr[0];
		$aname = $arr[1];
		$ptype = $arr[2];
		$works = new Works();
		$col = $works->col;
		$amtype = $works->amtype;
		$index = '1';
		foreach($col as $key=>$value){
			if($value == $ptype)
			{
				$index = $key;
				break;
			}
		}
		$ptype = $amtype[$index];
		$result = $arr[3];
		$sql = "update Achievement set result='".$result."' where stuid='".$stuid."' and aname='".$aname."' and amtype='".$ptype."'";
		$newModel->exec($sql);

		return $this->redirect()->toRoute('tea/defaults',array('controller'=>'Tea','action'=>'works'));
	}


	public function infoAction()
	{
		$view = new ViewModel(array('error'=>'#'));
		$view->setTemplate('tea/index/info.phtml');
		return $view;
	}


	public function academicAction()
	{
		$arr = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$tid = $_SESSION['num'];

		$sql = "select b.uid,b.name,a.comname,a.area,a.ctime,a.repname,a.documentation from Accom as a, Student as b where a.stuid=b.uid and result='wating' and b.tuid='".$tid."'";
		$res = $newModel->fetch($sql);
		$view = new ViewModel(array('error'=>'#','res'=>$res));
		$view->setTemplate('tea/index/academic.phtml');
		return $view;
	}

	public function projectAction()
	{
		$arr = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$tid = $_SESSION['num'];

		$sql = "select b.uid,b.name,a.ptype,a.pname,a.stime,a.etime,a.pwork,a.money,a.document from Project as a, Student as b where a.tid=b.uid and result='wating' and b.tuid='".$tid."'";
		$res = $newModel->fetch($sql);
		$view = new ViewModel(array('error'=>'#','res'=>$res));
		$view->setTemplate('tea/index/project.phtml');
		return $view;
	}

	public function worksAction(){
		$arr = json_decode(file_get_contents('php://input'), true);
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		session_start();
		$tid = $_SESSION['num'];

		$sql = "select b.uid,b.name,a.aname,a.amtype from Achievement as a, Student as b where a.stuid=b.uid and result='wating' and b.tuid='".$tid."'";
		$res = $newModel->fetch($sql);
		
		$view = new ViewModel(array('error'=>'#','res'=>$res));
		$view->setTemplate('tea/index/works.phtml');
		return $view;

	}
	
	public function showworksAction()
	{
		$works = new Works();
		$res = $works->res;
		$col = $works->col;
		$dir = $works->dir;
		$amtype = $works->amtype;

		$index = '1';
		$stuid = $_POST['stuid'];
		foreach($col as $key=>$value){
			if($value == $_POST['type'])
			{
				$index = $key;
				break;
			}
		}
		$type = $amtype[$index];
		$name = $_POST['name'];
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$newModel = new NewsModel($dbAdapter);
		$sql1 = "select idx from Achievement where stuid='".$stuid."' and amtype='".$amtype[$index]."' and aname='".$name."'";
		$row = $newModel->fetch($sql1);
		$idx = $row[0]['idx'];
		$sql2 = "select * from ".$dir[$index]." where idx='".$idx."'";
		$rr = $newModel->fetch($sql2);
		$view = new ViewModel(array('error'=>'#','res'=>$rr[0],'col'=>$_POST['type'],'cn'=>$res[$index],'dir'=>$dir[$index],'stuid'=>$stuid));
		$view->setTemplate('tea/index/showworks.phtml');
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



	public function newpassAction()
	{
		if(isset($_POST['Submit'])){
			$error = '#';
			session_start();
			$old = $_SESSION['password'];
			$oldpost = filter_var($_POST['oldpass'], FILTER_SANITIZE_STRING);
			$newpass1 = filter_var($_POST['newpass1'], FILTER_SANITIZE_STRING);
			$newpass2 = filter_var($_POST['newpass2'], FILTER_SANITIZE_STRING);


			if($old !== $oldpost || $newpass1 !== $newpass2 || empty($newpass1))
			{
				$view = new ViewModel(array('error'=>'更改错误'));
				$view->setTemplate('tea/index/info.phtml');
			}
			session_start();
			$_SESSION['password'] = $newpass1;
			$num = $_SESSION['num'];
			$identity = $_SESSION['identity'];
			$name = $_SESSION['name'];
			$data=array(
				'uid'=>$num,
				'upsd'=>md5($newpass1),
				'utype'=>$identity,
				'uname'=>$name
			);
			$sm = $this->getServiceLocator();
			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
			$tableGateway = new \Zend\Db\TableGateway\TableGateway('User',$dbAdapter, null, $resultSetPrototype);
			$userTable = new UserTable($tableGateway);
			$userTable->updata($data);	
		}
		return $this->redirect()->toRoute('tea/defaults',array('controller'=>'Tea','action'=>'info'));
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

