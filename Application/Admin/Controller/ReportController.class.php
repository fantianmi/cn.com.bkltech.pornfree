<?php
namespace Admin\Controller;

/**
*后台举报控制器
*@author  说好吥倣手  < 1018808441@qq.com >
*/

class ReportController extends AdminController
{
/*首页列表*/
	public function index(){
		$list = $this->lists('Report');
		foreach($list as &$v){
			$v['posttitle']=$this->showPostTitle($v['post_id']);
		}
		$this->assign('_list',$list);
		$this->display();
	}
	public function showPostTitle($id){
		$map['id']=$id;
		$post=M('forum_post')->where($map)->find();
		if(empty($post['title'])){
			return '该帖已删除';
		}else{
			return $post['title'];
		}
	}
/**
 * 会员状态修改
 */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] = array('in',$id);
        $this->delData('Report',$map);
    }
}


