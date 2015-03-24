<?php
namespace Admin\Controller;
// use Think\Controller;
class ZhengzhuangController extends AdminController{
	public function changjian(){
		$map = array(
			'type'=>0,
		);
		// $user = M('zhengzhuang')->where($map)->order('sort')->select();
        $user = M('zhengzhuang');
        $users = $this->lists($user,$map,'sort');
        $this->assign('hidden',1);
		$this->assign('_list',$users);
		// dump($user);
		$this->display();
	}
	// 症状的状态修改==========================================
	public function changeStatus(){
		$id = array_unique((array)I('id',0));
		$method = $_REQUEST['method'];
		if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ($method) {
        	case 'forbidUser':
        		$this->forbid('zhengzhuang', $map );
        		break;
        	case 'resumeUser':
        		$this->resume('zhengzhuang', $map );
        		break;
        	case 'deleteUser':
                $this->delete('zhengzhuang', $map );
                break;
        	default:
        		$this->error('参数非法');
        }
	}
	// 回收站试图===========================================
	public function listHui(){
		$huishou = M('zhengzhuang')->where("status=-1")->select();
		$this->assign('_list',$huishou);
		$this->display();
	}
	// 回收站还原处理
	public function huanyuan(){
		/*参数过滤*/
        $id = I('param.id');
        if(empty($id)){
            $this->error('请选择要操作的数据');
        }

        /*拼接参数并修改状态*/
        $Model  =   'zhengzhuang';
        $map    =   array();
        if(is_array($id)){
            $map['id'] = array('in', $id);
        }elseif (is_numeric($id)){
            $map['id'] = $id;
        }
        $this->restore($Model,$map);
	}
	// 清空回收站处理======----
	public function clear(){
        $id = I('param.id');
        if(empty($id)){
            $this->error('请选择要操作的数据');
        }
        // $this->error($id);
        $model = 'zhengzhuang';
        $map = array();
        if(is_array($id)){
            $map['id'] = array('in',$id);
        }else{
            $map['id'] = $id;
        }
        $this->delZhengzhuang($model,$map);
    }
    // 新增常见症状
    public function add(){
        if (IS_POST) {
            $name = $_POST['name'];
            $sort = $_POST['sort'];
            if(empty($name)){
                $this->error("名称不能为空！");
            }
            // 检查名称是否重复
            $maps['name'] = $name;
            $check = M('zhengzhuang')->where($maps)->find();
            if($check)
                $this->error('症状已存在');

            $data = array(
                'name'=>$name,
                'create_time'=>time(),
                'status'=>1,
                'type'=>0,
                // 'uid'=>1,
                'sort'=>$sort
            );
            if(M('zhengzhuang')->add($data)){
                $this->success("新增成功！",U('changjian'));
            }else{
                $this->error("新增失败！");
            }
        }
        $this->assign('title','新增');
        $this->display();
    }
    // 编辑常见症状
    public function edit(){
        if(IS_POST){
            $id = I('id');
            $name = I('name');
            $sort = I('sort',0);
            if(empty($name) || empty($id)){
                $this->error("参数非法！");
            }
            $data = array(
                'name'=>$name,
                'sort'=>$sort,
                'update_time'=>$_SERVER['REQUEST_TIME']
            );
            if(M('zhengzhuang')->where("id={$id}")->save($data)){
                $this->success("编辑成功！",U('changjian'));
            }else{
                $this->error("编辑失败！");
            }
        }
        $id = I('id');
        if(empty($id)){
            $this->error("参数错误！");
        }
        $zhengzhuang = M('zhengzhuang')->field('id,name,sort')->where("id={$id}")->find();
        $this->assign('info',$zhengzhuang);
        $this->assign('title','编辑');
        $this->display('add');
    }
/**
**未处理的意见列表
*/
    public function yijian(){
        $yijian = M('yijian')->where('status=1')->select();
        $this->assign('_list',$yijian);
        $this->display();
    }
/**
**已处理的意见列表
*/
    public function doYijian(){
        $yijian = M('yijian')->where('status=0')->select();
        $this->assign('title','已处理');
        $this->assign('hidden',true);
        $this->assign('_list',$yijian);
        $this->display('yijian');
    }
/**
**未处理的意见列表,设为已读
*/
    public function handle(){
        $id = array_unique((array)I('id',0));
        $method = $_REQUEST['method'];
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ($method) {
            case 'forbidUser':
                $this->forbid('yijian', $map );
                break;
            case 'resumeUser':
                $this->resume('yijian', $map );
                break;
            case 'deleteUser':
                $this->delZhengzhuang('yijian', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

/**
*@return 用户创建的症状列表
*/
    public function user(){
        $map = array(
            'type'=>1,
        );
        $user = M('zhengzhuang');
        $users = $this->lists($user,$map,'sort');
        $this->assign('hidden',0);
        $this->assign('_list',$users);
        $this->display('changjian');
    }

/**
*@return 最常见的症状
*/
    public function mostZheng(){
        $field = array(
            'zid',
            'count(*)'=>'count'
        );
        $list = M('zhengzhuang_user')->field($field)->group('zid')->order("count DESC")->select();
        $count = count($list);
        $pagesize = 10;
        $Page = new \Think\Page($count,$pagesize);
        $show = $Page->show();
        $zheng = M('zhengzhuang_user')->field($field)->group('zid')->order("count DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($zheng as &$v) {
            $v['name'] = $this->getZhengzhuang($v['zid']);
        }
        $this->assign('hidden',1);
        $this->assign('_list',$zheng);
        $this->assign('_page',$show);
        $this->display();
    }
/**
*@param $zid  症状的id
*@return 获取症状的名字（根据症状的id）
*/
    protected function getZhengzhuang($zid){
        $result = M('zhengzhuang')->field('name')->where("id={$zid} and status=1")->find();
        if($result){
            $return = $result['name'];
        }else{
            $return = '';
        }
        return $return;
    }

/**
*@param
*@return 运动健身列表
*/
    public function yundong(){
        $list = $this->lists('yundong','','sort');
        // $list = M('yundong')->order('sort')->select();
        $this->assign('_list',$list);
        $this->display();
    }

/**
*@param
*@return 添加运动健身页面显示兼处理
*/
    public function addYundong(){
        if(IS_POST){
            $name = I('name');
            $sort = I('sort',0);
            if(empty($name)) $this->error('名称不能为空！');
            $data = array(
                'name'=>$name,
                'create_time'=>$_SERVER['REQUEST_TIME'],
                'sort'=>$sort
            );
            if(M('yundong')->add($data)){
                $this->success('新增成功！',U('yundong'));
            }else{
                $this->error('新增失败！');
            }
        }else{
            $this->assign('title','添加');
            $this->display();
        }
    }

/**
*@param
*@return 编辑运动健身页面显示兼处理
*/
    public function editYundong(){
        if(IS_POST){
            $name = I('name');
            $sort = I('sort',0);
            $id = I('id');
            if(empty($name) || empty($id)) $this->error('参数非法！');
            $data = array(
                'name'=>$name,
                'update_time'=>$_SERVER['REQUEST_TIME'],
                'sort'=>$sort,
            );
            if(M('yundong')->where("id={$id}")->save($data)){
                $this->success('编辑成功！',U('yundong'));
            }else{
                $this->error('编辑失败！');
            }
        }else{
            $id = I('id');
            if(empty($id)) $this->error('参数非法！');
            $info = M('yundong')->where("id={$id}")->find();
            $this->assign('info',$info);
            $this->assign('title','编辑');
            $this->display('addYundong');
        }
    }
/**
*@param
*@return 修改运动的状态,包括删除
*/
    public function statusYundong(){
        $method = I('method','');
        $id = I('id');
        if(empty($id)) $this->error('参数非法！');
        switch ($method) {
            case 'forbidUser':
                $result = M('yundong')->where("id={$id}")->setField('status',0);
                break;
            case 'resumeUser':
                $result = M('yundong')->where("id={$id}")->setField('status',1);
                break;
            case 'deleteUser':
                $result = M('yundong')->where("id={$id}")->delete();
                break;
            default:
                $result = false;
                break;
        }
        if($result){
            $this->success('操作成功！',U('yundong'));
        }else{
            $this->error('操作失败！');
        }
    }

    
}


