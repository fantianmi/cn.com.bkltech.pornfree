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
		$this->assign('_list',$list);
		$this->display();
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


