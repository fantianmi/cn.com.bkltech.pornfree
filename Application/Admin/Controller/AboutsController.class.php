<?php
namespace Admin\Controller;

class AboutsController extends AdminController
{
/**
*关于我们编辑页面
*/
	public function index(){
		$data = M('abouts')->find('1');
		$this->assign('data',$data);
		$this->display();
	}
/**
*编辑关于我们的方法
*/
	public function edit(){
		$texts = I('texts');
		$data = array(
			'id'      => '1',
			'content' => $texts,
		);
		$re = M('abouts')->save($data);
		if($re){
			$this->success('更新成功');
		}else{
			$this->error('更新失败');
		}
	}
}


