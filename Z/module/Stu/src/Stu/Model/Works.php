<?php
namespace Stu\Model;

class Works
{
	public $res = array();
	public $col = array();
	public $amtype = array();
	public $dir = array();
	public function __construct()
	{
		$this->res = array(
			'1'=>array('name'=>'论文名','bname'=>'刊物名','pst'=>'论文状态','ptime'=>'发表时间','idxtype'=>'索引类型','papersblg'=>'论文归属','path'=>'文件'),
			'2'=>array('awtype'=>'奖励等级','prizetype'=>'获奖等级','ranking'=>'排名','awtime'=>'获奖时间','name'=>'奖励名字','path'=>'文件'),
			'3'=>array('name'=>'标准名称','stype'=>'标准级别','stime'=>'标准发布时间','path'=>'文件'),
			'4'=>array('name'=>'报告名称','rptype'=>'报告类型','rpobject'=>'报告对象','rptime'=>'报告时间','rpranking'=>'贡献度排名','path'=>'文件'),
			'5'=>array('name'=>'专利名称','ptid'=>'专利号','pttime'=>'发布时间','ptrannking'=>'贡献度排名','pst'=>'状态','path'=>'文件'),
			'6'=>array('name'=>'平台名称','plobject'=>'服务对象','pltime'=>'上线时间','plranking'=>'贡献度排名','path'=>'文件'),
			'7'=>array('name'=>'教材名','tbpress'=>'出版社','trank'=>'贡献度排名','path'=>'文件')
		);
		$this->col = array(
			'1'=>'论文',
			'2'=>'奖励',
			'3'=>'标准',
			'4'=>'报告',
			'5'=>'专利',
			'6'=>'平台',
			'7'=>'教材'
		);
		$this->dir = array(
			'1'=>'Paper',
			'2'=>'Award',
			'3'=>'Standard',
			'4'=>'Report',
			'5'=>'Patent',
			'6'=>'Platform',
			'7'=>'Textbook'
		);
		$this->amtype = array(
			'1'=>'paper',
			'2'=>'award',
			'3'=>'standard',
			'4'=>'report',
			'5'=>'patent',
			'6'=>'platform',
			'7'=>'textbook'
		);
	}
}
