<?php
namespace Detail\Controller;
use Think\Controller;

class IndexController extends Controller
{
/**
*APP端查看文章的详情
*@param   $id   文章的id
*/
	function index()
	{
		$id = I('id','','intval');
		if(empty($id)) exit( err(100) );
		$data = D('Document')->detail($id);
		$this->assign('data',$data);
		$this->display();
	}
/**
*关于我们页面
*/
	public function about(){
		$data = M('abouts')->find('1');
		$this->assign('content',$data['content']);
		$this->display();
	}

	// 刷头像的方法
	/*public function shuaAv(){
		$uid = array();
		for ($a=298; $a <= 347; $a++) { 
			$uid[] = $a;
		}

		$i = 1;
    	foreach ($uid as $val) {
    		$data[] = array(
    			'uid' => $val,
    			'path' => '/Avatar/20150313/'.$i.'.jpg',
    			'create_time' => NOW_TIME,
    			'status' => 1,
    			'is_temp' => 1
    		);
    		$i++;
    	}
    	// $re = M('Avatar')->addAll($data);
    	// dump($re);
	}*/
}




