<?php
namespace Stu\Controller;

define('ROOT',dirname(__FILE__).'/');
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
				$view->setTemplate('stu/index/info.phtml');
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
		return $this->redirect()->toRoute('stu/defaults',array('controller'=>'Stu','action'=>'info'));
	}


	public function infoAction()
	{
		$view = new ViewModel(array('error'=>'#'));
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

	public function downloadAction()
	{
		$filename = json_decode(file_get_contents('php://input'), true);
		header("content-disposition:attachment;filename=$filename");
		header("content-length:".filesize($filename));
		readfile($filename);
		// return $this->redirect()->toRoute("stu/defaults",array('controller'=>'Stu','action'=>'academic'));
	}

	public function academicAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('Accom',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$res = $userTable->fetchAll();
		$view = new ViewModel(array('error'=>'#','res'=>$res));
		$view->setTemplate('stu/index/academic.phtml');
		return $view;
	}

	public function acsubmitAction()
	{
		if(isset($_POST['Submit'])){
			session_start();
			$stuid = $_SESSION['num'];
			$comname = $_POST['acname'];
			$area = $_POST['acplace'];
			$ctime = $_POST['time'];
			$rename = $_POST['name'];
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["file"]["name"]);
			$extension = end($temp);
			if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/jpg")
			|| ($_FILES["file"]["type"] == "image/pjpeg")
			|| ($_FILES["file"]["type"] == "image/x-png")
			|| ($_FILES["file"]["type"] == "image/png"))
			&& in_array($extension, $allowedExts))
			{
				$path = "/var/www/Z/public/img/";
				if(!is_dir($path.$stuid)){
					mkdir($path.$stuid, 0777, true);
					$view = new ViewModel(array('error'=>$path.$stuid));
					$view->setTemplate('stu/index/academic.phtml');
					return $view;
				}
				$name = $stuid."/".$rename . $_FILES["file"]["name"];
				$documentation = $name;
				if(!is_uploaded_file($_FILES['file']['tmp_name'])){
					$view = new ViewModel(array('error'=>'上传失败'));
					$view->setTemplate('stu/index/academic.phtml');
					return $view;
				}
				if(!move_uploaded_file($_FILES["file"]["tmp_name"], $path.$documentation))
				{
					$view = new ViewModel(array('error'=>"上传失败"));
					$view->setTemplate('stu/index/academic.phtml');
					return $view;

				}
				$data = array(
					'stuid'=>$stuid,
					'comname'=>$comname,
					'area'=>$area,
					'ctime'=>$ctime,
					'repname'=>$rename,
					'documentation'=>$documentation
				);
				$sm = $this->getServiceLocator();
				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
				$tableGateway = new \Zend\Db\TableGateway\TableGateway('Accom',$dbAdapter, null, $resultSetPrototype);
				$tableGateway->insert($data);
				return $this->redirect()->toRoute('stu/defaults',array('controller'=>'Stu','action'=>'academic'));
			}
		else{
			$view = new ViewModel(array('error'=>'上传失败'));
			$view->setTemplate('stu/index/academic.phtml');
				return $view;
			}
		}
		else
			return $this->redirect()->toRoute('stu/defaults',array('controller'=>'Stu','action'=>'academic'));
	}

	public function projectAction()
	{
		$view = new ViewModel(array('error'=>'#'));
		$view->setTemplate('stu/index/project.phtml');
		return $view;
	}

	public function worksAction()
	{
		$view = new ViewModel(array('error'=>'#'));
		$view->setTemplate('stu/index/works.phtml');
		return $view;
	}

}

