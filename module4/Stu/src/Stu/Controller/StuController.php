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
use Stu\Model\Works;
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


	public function pasubmitAction(){
		$taid = $_POST['stuid'];
		$cid = $_POST['cid'];
		$tuid = $_POST['tid'];
		$workReport = $_POST['report'];
		$result = 'wating';
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$newModel = new NewsModel($dbAdapter);
		$sql = "update Raletionta set workReport='".$workReport."',result='wating' where cid='".$cid."' and taid='".$taid."' and tuid='".$tuid."'";	

		$newModel->exec($sql);
		return $this->redirect()->toRoute('stu/defaults',array('controller'=>'Stu','action'=>'show'));
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

		$sql2 = 'SELECT a.cid,b.cname,b.ctime,b.session,a.tid,c.tname,d.result FROM Stuvolunteer as a,Course as b, Raletionclass as c,  Raletionta as d WHERE a.stuid="'.$num.'" and a.stuid=b.taid and a.stuid=b.taid and a.cid=b.cid and a.cid=c.cid and a.cid=d.cid and a.tid=c.tuid and a.tid=c.tuid';
		// $sql2 = 'SELECT a.cid,b.cname,b.ctime,b.session,a.tid,c.tname FROM Stuvolunteer as a,Course as b, Raletionclass as c WHERE a.cid=b.cid  and a.tid=c.tuid and a.result="allow" and a.stuid="'.$num.'" and b.cid=c.cid';
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
		session_start();
		$file_dir = "/var/www/public/img/".$_SESSION['num']."/";        //下载文件存放目录    
		//检查文件是否存在    
		if (! file_exists ( $file_dir . $filename )) {    
			$sm = $this->getServiceLocator();
			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
			$tableGateway = new \Zend\Db\TableGateway\TableGateway('Accom',$dbAdapter, null, $resultSetPrototype);
			$userTable = new UserTable($tableGateway);
			$res = $userTable->fetchAll();
			$view = new ViewModel(array('error'=>'error','res'=>$res));
			$view->setTemplate('stu/index/academic.phtml');
			return $view;
			// header('HTTP/1.1 404 NOT FOUND');
		} else {    
			// $sm = $this->getServiceLocator();
			// $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			// $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
			// $tableGateway = new \Zend\Db\TableGateway\TableGateway('Accom',$dbAdapter, null, $resultSetPrototype);
			// $userTable = new UserTable($tableGateway);
			// $res = $userTable->fetchAll();
			// $view = new ViewModel(array('error'=>'E','res'=>$res));
			// $view->setTemplate('stu/index/academic.phtml');
			// return $view;
			// Header("Expires: 0");
			// Header("Pragma: public");
			// Header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			// Header("Cache-Control: public");
			// Header("Content-Length: ". filesize($file_dir.$filename));
			// Header("Content-Type: application/octet-stream");
			// Header("Content-Disposition: attachment; filename=".$file_dir.$filename);
			// readfile($file_dir.$filename);
			// exit();
			// header( "Content-Disposition:  attachment;  filename=".$filename); //告诉浏览器通过附件形式来处理文件
			// header('Content-Length: ' . filesize($file_dir.$filename)); //下载文件大小
			// readfile($file_dir.$filename);  //读取文件内容
			// $file = fopen ( $file_dir . $filename, "rb" );
			//
			// Header ( "Content-type: application/octet-stream" );
			// Header ( "Accept-Ranges: bytes" );
			// Header ( "Accept-Length: " . filesize ( $file_dir . $filename ) );
			// Header ( "Content-Disposition: attachment; filename=" . $filename );
			//
			// echo fread ( $file, filesize ( $file_dir . $filename ) );
			// fclose ( $file );
		}

		return $this->redirect()->toRoute("stu/defaults",array('controller'=>'Stu','action'=>'academic'));
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
				$path = "/var/www/Z/public/img/".$stuid."/";
				if(!is_dir($path)){
					if(!mkdir($path, 0777, true)){
						$view = new ViewModel(array('error'=>$path));
						$view->setTemplate('stu/index/academic.phtml');
						return $view;
					}
				}
				$name = $rename .date('h:i:s', time()). $_FILES["file"]["name"];
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
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('Project',$dbAdapter, null, $resultSetPrototype);
		$userTable = new UserTable($tableGateway);
		$res = $userTable->fetchAll();
		$view = new ViewModel(array('error'=>'#','res'=>$res));
		$view->setTemplate('stu/index/project.phtml');
		return $view;
	}

	public function getMillisecond(){
		list($s1,$s2)=explode(' ',microtime());
		return sprintf('%.0f',(floatval($s1)+floatval($s2))*1000);
	}

	public function prosubmitAction(){
		if(isset($_POST['Submit'])){
			session_start();
			$tid = $_SESSION['num'];
			$time = $_POST['date-range-picker'];
			$arr = explode(" - ",$time);

			$temp = explode(".", $_FILES["file"]["name"]);
			$extension = end($temp);
			
			$path = "/var/www/Z/public/project/".$tid."/";

			if(!is_dir($path)){
				if(!mkdir($path, 0777, true)){
					$view = new ViewModel(array('error'=>'上传失败'));
					$view->setTemplate('stu/index/project.phtml');
					return $view;
				}
			}


			$name = date('h:i:s', time()). $_FILES["file"]["name"];

			$documentation = $name;
			if(!is_uploaded_file($_FILES['file']['tmp_name'])){
				$view = new ViewModel(array('error'=>'上传失败1'));
				$view->setTemplate('stu/index/project.phtml');
				return $view;
			}
			if(!move_uploaded_file($_FILES["file"]["tmp_name"],$path.$documentation))
			{
				$view = new ViewModel(array('error'=>"上传失败"));
				$view->setTemplate('stu/index/project.phtml');
				return $view;

			}

			$data = array(
				'gaveid'=>$_POST['acname'],
				'stime'=>$arr[0],
				'pname'=>$_POST['abname'],
				'tid'=>$tid,
				'etime'=>$arr[1],
				'money'=>(int)$_POST['money'],
				'ptype'=>$_POST['acplace'],
				'pwork'=>$_POST['name'],
				'document'=>$documentation
			);

			$sm = $this->getServiceLocator();
			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
			$tableGateway = new \Zend\Db\TableGateway\TableGateway('Project',$dbAdapter, null, $resultSetPrototype);

			if(!$tableGateway->insert($data)){
				$view = new ViewModel(array('error'=>'提交失败','res'=>$res));
				$view->setTemplate('stu/index/project.phtml');
				return $view;
			}
		}

		return $this->redirect()->toRoute('stu/defaults',array('controller'=>'Stu','action'=>'project'));
	}

	public function worksAction()
	{
		$works = new Works();
		$res = $works->res;
		$col = $works->col;

		// $ress = array(
			// '1'=>array('name'=>'论文名','bname'=>'刊物名','pst'=>'论文状态','ptime'=>'发表时间','idxtype'=>'索引类型','paperblg'=>'论文归属','path'=>'文件'),
			// '2'=>array('awtype'=>'奖励等级','prizetype'=>'获奖等级','ranking'=>'排名','awtime'=>'获奖时间','name'=>'奖励名字','path'=>'路径'),
			// '3'=>array('name'=>'标准名称','stype'=>'标准级别','stime'=>'标准发布时间','path'=>'文件'),
			// '4'=>array('name'=>'报告名称','rptype'=>'报告类型','rpobject'=>'报告对象','rptime'=>'报告时间','rpranking'=>'贡献度排名','path'=>'文件'),
			// '5'=>array('name'=>'专利名称','ptid'=>'专利号','pttime'=>'发布时间','ptrannking'=>'贡献度排名','pst'=>'状态','path'=>'文件'),
			// '6'=>array('name'=>'平台名称','plobject'=>'服务对象','pltime'=>'上线时间','plranking'=>'贡献度排名','path'=>'文件'),
			// '7'=>array('name'=>'教材名','tbpress'=>'出版社','trank'=>'贡献度排名','path'=>'文件')
		// );
		// $col = array(
			// '1'=>'论文',
			// '2'=>'奖励',
			// '3'=>'标准',
			// '4'=>'报告',
			// '5'=>'专利',
			// '6'=>'平台',
			// '7'=>'教材'
		// );
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$tableGateway = new \Zend\Db\TableGateway\TableGateway('Achievement',$dbAdapter, null, $resultSetPrototype);
		session_start();
		$result = $tableGateway->select(array('stuid'=>$_SESSION['num']));
		// $res = array();
		$resultSet = new \Zend\Db\ResultSet\ResultSet();
		$resultSet->initialize($result);
		$res = $resultSet->toArray();
		$view = new ViewModel(array('error'=>'#','res'=>$res));
		$view->setTemplate('stu/index/works.phtml');
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
			session_start();
			$stuid = $_SESSION['num'];
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
			$view = new ViewModel(array('error'=>'#','res'=>$rr[0],'col'=>$_POST['type'],'cn'=>$res[$index],'dir'=>$dir[$index]));
			$view->setTemplate('stu/index/showworks.phtml');
			return $view;
	}

	public function workssubmitAction()
	{
		$works = new Works();
		$res = $works->res;
		$col = $works->col;
		$dir = $works->dir;
		$amtype = $works->amtype;

		if(isset($_POST['Submit'])){
			$label = $_POST['label'];
//
		// $view = new ViewModel(array('error'=>'#','col'=>$col[$value],'res'=>$res[$value],'label'=>$label));
		// $view->setTemplate('stu/index/addworks.phtml');
		// return $view;
			$index = '1';
			foreach($col as $key=>$value){
				if($value == $label){
					$index = $key;
					break;
				}
			}
			$arr = array();
			foreach($res as $key=>$value){
				if($key == $index){
					$arr = $value;
					break;
				}
			}
			session_start();
			$stuid = $_SESSION['num'];
			$path = "/var/www/Z/public/works/".$stuid."/".$dir[$index]."/";
			if(!is_dir($path)){
				if(!mkdir($path, 0777, true)){
					$view = new ViewModel(array('error'=>'上传失败','col'=>$label,'res'=>$arr));
					$view->setTemplate('stu/index/addworks.phtml');
					return $view;				
				}
			}
			$name = date('h:i:s', time()). $_FILES["path"]["name"];
			if(!is_uploaded_file($_FILES['path']['tmp_name'])){
				$view = new ViewModel(array('error'=>'上传失败','col'=>$label,'res'=>$arr));
				$view->setTemplate('stu/index/addworks.phtml');
				return $view;
			}
			if(!move_uploaded_file($_FILES["path"]["tmp_name"], $path.$name))
			{
				$view = new ViewModel(array('error'=>'上传失败','col'=>$label,'res'=>$arr));
				$view->setTemplate('stu/index/addworks.phtml');
				return $view;
			}
			$data = array();
			foreach($arr as $key=>$value){
				if($key == 'path'){
					$data[$key] = $name;
				}
				else
					$data[$key] = $_POST[$key];
			}

			$sm = $this->getServiceLocator();
			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
			$tableGateway = new \Zend\Db\TableGateway\TableGateway($dir[$index],$dbAdapter, null, $resultSetPrototype);
			$newModel = new NewsModel($dbAdapter);

			$data1 = array(
				'amtype'=>$amtype[$index],
				'stuid'=>$stuid,
				'aname'=>$data['name'],
				'result'=>'wating'
			);
			if(!$newModel->insert('Achievement',$data1)){
				$view = new ViewModel(array('error'=>'上传失败','col'=>$label,'res'=>$arr));
				$view->setTemplate('stu/index/addworks.phtml');
				return $view;
			}
			$row = $newModel->fetch("select idx from Achievement where stuid='".$stuid."' and amtype='".$amtype[$index]."' and aname='".$data['name']."'");
		// $view = new ViewModel(array('error'=>'#','col'=>$col[$value],'res'=>$res[$value],'data'=>$data));
		// $view->setTemplate('stu/index/addworks.phtml');
		// return $view;
			$data['idx'] = $row[0]['idx'];

			$tableGateway->insert($data);

		}

		return $this->redirect()->toRoute('stu/defaults',array('controller'=>'Stu','action'=>'works'));
	}

	public function addworksAction(){
		$works = new Works();
		$res = $works->res;
		$col = $works->col;
		// $res = array(
			// '1'=>array('name'=>'论文名','bname'=>'刊物名','pst'=>'论文状态','ptime'=>'发表时间','idxtype'=>'索引类型','paperblg'=>'论文归属','path'=>'文件'),
			// '2'=>array('awtype'=>'奖励等级','prizetype'=>'获奖等级','ranking'=>'排名','awtime'=>'获奖时间','name'=>'奖励名字','path'=>'路径'),
			// '3'=>array('name'=>'标准名称','stype'=>'标准级别','stime'=>'标准发布时间','path'=>'文件'),
			// '4'=>array('name'=>'报告名称','rptype'=>'报告类型','rpobject'=>'报告对象','rptime'=>'报告时间','rpranking'=>'贡献度排名','path'=>'文件'),
			// '5'=>array('name'=>'专利名称','ptid'=>'专利号','pttime'=>'发布时间','ptrannking'=>'贡献度排名','pst'=>'状态','path'=>'文件'),
			// '6'=>array('name'=>'平台名称','plobject'=>'服务对象','pltime'=>'上线时间','plranking'=>'贡献度排名','path'=>'文件'),
			// '7'=>array('name'=>'教材名','tbpress'=>'出版社','trank'=>'贡献度排名','path'=>'文件')
		// );
		// $col = array(
			// '1'=>'论文',
			// '2'=>'奖励',
			// '3'=>'标准',
			// '4'=>'报告',
			// '5'=>'专利',
			// '6'=>'平台',
			// '7'=>'教材'
		// );
		$value = $_POST['idt'];

		$view = new ViewModel(array('error'=>'#','col'=>$col[$value],'res'=>$res[$value]));
		$view->setTemplate('stu/index/addworks.phtml');
		return $view;

	}

}

