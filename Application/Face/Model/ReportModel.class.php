<?php
namespace Face\Model;
use Think\Model;

/**
*举报表模型
*@author   说好吥倣手   < 1018808441@qq.com >
*/

class ReportModel extends Model
{
/**
*新增举报
*/
	public function addData($data)
	{
		$this->c_null($data['post_id']);
		$this->c_null($data['uid']);
		$data['create_time'] = NOW_TIME;
		$re = $this->add($data);
		return $re ? true : false;
	}

/*自定义检测字段( 不允许为空 )*/
	protected function c_null($data)
	{
		if(empty($data)) exit( err(100) );
	}
}

