<?php
namespace Face\Controller;
use Think\Controller;
class ZhengzhuangController extends Controller{

/**
获取后台的常见症状(如果传入uid还包括用户的症状)
*/
    public function getZheng(){
    	$uid = I('uid');
        $sql = "select z.id zid,z.uid,z.name,z.create_time from thinkox_zhengzhuang z left join (select *,count(*) uc from thinkox_zhengzhuang_user group by zid) u ON z.id=u.zid where z.type=0 and z.status=1 order by u.uc DESC";
        $result = M()->query($sql);
        foreach ($result as &$v) {
            $v['type'] = "sys";
        }
    	if(!empty($uid)){
    		$user = M('zhengzhuang_user')->field('zid,uid,create_time')->where("uid={$uid}")->select();
            // $user = M('zhengzhuang')->field($field)->where("uid={$uid} and status=1")->select();
            if($user){
                foreach ($user as &$v) {
                    $v['name'] = $this->getZhengzhuang($v['zid']);
                    $v['type'] = 'user';
                }
            }else{
                $user = array();
            }
            $data = array_merge($result,$user);
            exit(suc($data));
    	}
        exit(suc($result));
    }

/**
*@return 获取系统里面的所有症状
*/
    public function getAllZheng(){
        $pagenum = I('pagenum',1);
        $pagesize = I('pagesize',21);
        $list = M('zhengzhuang')->where('status=1')->page($pagenum,$pagesize)->select();
        $totalcount = M('zhengzhuang')->where('status=1')->count();
        echo $this->sucp($pagenum,$pagesize,$totalcount,$list);
    }


/**
*@param uid : 用户id
*@return 获取用户自己的症状
*/
    public function getZhengUser(){
        $uid = I('uid');
        if(empty($uid)) exit( $this->err(100));
        $this->checkIsScore($uid);
        $user = M('zhengzhuang_user')->where("uid={$uid}")->select();
        if(!$user) exit( $this->suc());
        foreach ($user as &$v) {
            $v['name'] = $this->getZhengzhuang($v['zid']);
            $v['usercount'] = $this->getUserCount($v['zid']);
            $v['score'] = D('ZhengzhuangScore')->getZScore($uid,$v['zid']);
        }
        $result = $this->check($uid);
        $arr = array(
            'zheng' => $user,
            'check' => $result
        );
        echo $this->suc($arr);
    }

    public function check($uid = ''){
        $y   = date('Y',NOW_TIME);
        $m   = date('n',NOW_TIME);
        $d   = date('j',NOW_TIME);
        if(empty($uid)) exit(err(100));
        $arr = array();
        for ($i=1; $i <= $d; $i++) { 
            $arr[] = $i;
        }
        foreach ($arr as $v) {
            $start = mktime(0,0,0,$m,$v,$y);
            $last  = mktime(24,0,0,$m,$v,$y);
            if(M('check_info')->where("uid = {$uid} and ctime > $start and ctime < $last")->find()){
                $result[] = array('key'=>$v,'value'=>1);
            }else{
                $result[] = array('key'=>$v,'value'=>0);   
            }
        }
        return $result;
    }
    public function checks($uid = ''){
        $y   = date('Y',NOW_TIME);
        $m   = date('n',NOW_TIME);
        $d   = date('j',NOW_TIME);
        if(empty($uid)) exit(err(100));
        $arr = array();
        for ($i=1; $i <= $d; $i++) {
            $arr[] = $i;
        }
        foreach ($arr as $v) {
            $start = mktime(0,0,0,$m,$v,$y);
            $last  = mktime(24,0,0,$m,$v,$y);
            if(M('check_info')->where("uid = {$uid} and ctime > $start and ctime < $last")->find()){
                $result[] = array('key'=>$v,'value'=>1);
            }else{
                $result[] = array('key'=>$v,'value'=>0);   
            }
        }
        dump($result);
        $w = mktime(0,0,0,$m,1,$y);
        $re = M('check_info')->where("ctime > {$w} and uid={$uid}")->order("ctime")->getField("ctime",true);
        echo date("Y-m-d H:i:s",'1420190127');
        dump(check_infos($re));
        return $result;
    }

/**
用户创建症状处理
*/
    public function addZheng(){
        $uid = I('uid');
        $name = I('name');
        if (empty($uid) || empty($name) || $uid == 1) {
            exit( $this->err(100));
        }
        $result = M('zhengzhuang')->where("name='{$name}'")->find();
        if($result){
            // $zid = $result['id'];
            exit( err(400) );
        }else{
            $data = array(
                'name'=>$name,
                'uid'=>$uid,
                'type'=>1,
                'create_time'=>$_SERVER['REQUEST_TIME'],
                'status'=>1
            );
            $zid = M('zhengzhuang')->add($data);
        }

        $zheng = M('zhengzhuang')->field('id zid,name')->where("id={$zid}")->find();
        if($zheng){
            exit(suc($zheng));
        }else{
            exit(err());
        }
    }
/*上面用户创建症状处理拆出来的接口*/
    public function addUserZheng(){
        $zid   = I('zid');
        $uid   = I('uid');
        if(empty($uid)) exit(err(100));
        if(empty($zid)){
            M('zhengzhuang_user')->where("uid={$uid}")->delete();
            echo( suc() );
        }else{
            $zid   = explode(",",$zid);

            $datas = array();
            foreach ($zid as $v) {
                $datas[]  =  array(
                    'zid'         => $v,
                    'uid'         => $uid,
                    'create_time' => NOW_TIME
                );
            }
            M('zhengzhuang_user')->where("uid={$uid}")->delete();
            if(M('zhengzhuang_user')->addAll($datas)){
                $strZid = implode(',',$zid);
                $zheng  = M('zhengzhuang')->field('id zid,name')->where("id in({$strZid})")->select();
                foreach ($zheng as &$val) {
                    /*获取症状的创建世界*/
                    $val['create_time'] = M('zhengzhuang_user')->where("zid={$val['zid']} and uid={$uid}")->getField('create_time');

                    $val['uid'] = $uid;
                }
                echo $this->suc($zheng);
            }else{
                // echo $this->err();
            }
        }
    }
/**
用户删除症状处理
*/
	public function delZheng(){
		$zid = I('zid');
        $uid = I('uid');
		if(empty($zid) || empty($uid)){
			exit( $this->err(100));
		}
		if(M('zhengzhuang_user')->where("zid={$zid} and uid={$uid}")->delete()){
			echo $this->suc();
		}else{
			echo $this->err();
		}
	}
/**
添加精虫上脑
*/
    public function addJing(){
        $uid = I('uid');
        if (empty($uid)) { exit( $this->err('100'));}
        $content = I('content');
        $data = array(
            'uid'=>$uid,
            'ctime'=>time(),
            'content'=>op_t($content),
        );
        if(M('jingchong')->add($data)){
            $this->todayRecordCount($uid);
            /*向记录表插入数据*/
            $datas = array(
                'uid'         => $uid,
                'content'     => $content,
                'create_time' => NOW_TIME,
                'type'        => 2,
            );
            M('jing_check')->add($datas);
            echo $this->suc();
        }else{
            echo $this->err();
        }
    }
/**
精虫上脑列表
*/
    public function listJing(){
        $uid = I('uid');
        if(empty($uid)) exit( $this->err(100));
        $pagenum = I('pagenum',1);
        $pagesize = I('pagesize',10);
        $list = M('jingchong')->where("uid={$uid}")->order('ctime DESC')->page($pagenum,$pagesize)->select();
        $totalcount = M('jingchong')->where("uid={$uid}")->count();
        echo $this->sucp($pagenum,$pagesize,$totalcount,$list);
    }

// 今日精虫上脑的次数(改成首页（首页）了，多了两个参数)
    public function getCount(){
        $uid = I('uid');
        if(empty($uid)) exit( $this->err(100));
        $start = strtotime(date('Ymd'));
        $end = $start+3600*24;
        $map = array(
            'ctime'=>array('gt',$start),
            'uid'=>array('eq',$uid)
        );
        $con_num = M('check_info')->where("uid={$uid}")->order('ctime DESC')->find();
        $conNum = $con_num['con_num'];//连签的次数
        $time = M('check_info')->where("uid={$uid}")->order('ctime DESC')->page($conNum,1)->select();

        $count = M('jingchong')->where($map)->where("ctime < {$end}")->count();
        $totalcount = M('jingchong')->where("uid={$uid}")->count();
        $diwei = query_user(array('title'),$uid);
        $data = array(
            'diwei'=>$diwei['title'],
            "count"=>$count,
            'totalcount'=>$totalcount,
            'time'=>$time[0]['ctime']
        );
        echo $this->suc($data);
    }
	/**
	*向数据表插入（ 为了app统计图 ）
	*/
    public function addZhengScoreRecord($uid,$zid,$score,$create_time){
    	if($create_time!=''&&$create_time!=null){
        	$first  = $create_time;
    	}else{
        	$first  = strtotime(date('Ymd',NOW_TIME));
    	}
        $last   = $first + 24*3600;
        $map['create_time'] = array('between',"{$first},{$last}");
        $map['uid']         = $uid;
        $map['zid']         = $zid;
        $model = M('zhengzhuang_record');
        $re = $model->where($map)->find();
        if($re){//更新
            // $re['create_time'] = strtotime(date('Ymd',NOW_TIME));
            $re['score']       = $score;
            $res = $model->save($re);
        }else{//新增
            $data = array(
                'uid'         => $uid,
                'zid'         => $zid,
                'score'       => $score,
                'create_time' => $first,
            );
            $model->add($data);
        }
    }
    /**
     * 判断用户是否给自己症状打分，如果没有则进行自动打分，判断从最后一次打分时间到昨天的数据
     * if昨天有打分则不用操作，则进行打分
     */
    public function checkIsScore($uid){
    	$list=M('zhengzhuang_user')->where("uid={$uid}")->select();
    	$checkDate=strtotime(date('Ymd',NOW_TIME))-86400;
    	foreach($list as $vals){
    		$map['zid']=$vals['zid'];
    		$map['uid']=$uid;
    		$zhengzhuang_record=M('zhengzhuang_record')->where($map)->order('create_time desc')->limit(1)->find();
    		$ctime=$zhengzhuang_record['create_time'];
    		if($ctime==''||$ctime==null){
    			$this->addZhengScoreRecord($uid,$vals['zid'],5);
    		}else if($ctime<$checkDate){
    			$this->autoInsertScore($uid,$vals['zid'],$ctime,$zhengzhuang_record['score']);
    		}
    	}
    }
    /**
     * 自动插入分数
     */
    public function autoInsertScore($uid,$zid,$start_time,$recent_score){
    	while($start_time<=strtotime(date('Ymd',NOW_TIME))-86400){
    		$this->addZhengScoreRecord($uid,$zid,$recent_score,$start_time);
    		$start_time+=86400;
    	}
    }

// 给自己的症状评分
    public function score(){
        $score = I('score');        
        $data  = json_decode($score,true);
        foreach ($data as &$val) {
            $val['create_time'] = NOW_TIME;
        }
        if(M('zhengzhuang_score')->addAll($data)){
            /*向统计表插入数据，方便app统计图*/
            foreach ($data as $vals) {
                $this->addZhengScoreRecord($vals['uid'],$vals['zid'],$vals['score']);
            }
            echo $this->suc();
        }else{
            echo $this->err();
        }
    }
	// 症状测评月统计图的评分
    public function getScore(){
        $uid   = I('uid');
        $limit = I('size',10);
        if(empty($uid)) exit( $this->err(100));
        $this->checkIsScore($uid);
        $model = M('zhengzhuang_record');
        $zhengzhuang = M('zhengzhuang');
        $zid = M('zhengzhuang_user')->where("uid={$uid}")->getField('zid',true);
        foreach ($zid as $val) {
            $map['uid'] = $uid;
            $map['zid'] = $val;
            /*获取症状的名称*/
            $zName = $zhengzhuang->where("id={$val}")->getField('name');

            $data = $model->where($map)->limit($limit)->select();
            $datas[] = array('name'=>$zName,'datas'=>$data?$data:'');
        }
        echo suc($datas);
    }

// 我的经验值接口
// 根据用户的uid获取member里面的score字段
    public function uScore(){
        $uid = I('uid');
        if(!$uid) exit( $this->err(100));
        $result = M('member')->where("uid={$uid}")->getField('score');
        $data = array('score'=>$result);
        echo $this->suc($data);
    }

/**
*@description   获取用户所有症状的分数( 只获取某一天的 )
*@param    $uid    用户的id
*@param    $time   时间戳( 只获取时间戳当天的症状分数 )
*@return   array   
*/
    public function getDayZhengScore(){
        $uid  = I('uid');
        $time = I('time');
        if(empty($uid) || empty($time)) exit( err(100) );
        /*处理时间范围*/
        $t  = strtotime(date('Ymd',$time));
        $t1 = $t;
        $t2 = $t + 24*3600;
        $map['create_time'] = array('between',"{$t1},{$t2}");
        /*先获取用户的所有症状*/
        $uZid = M('zhengzhuang_user')->where("uid={$uid}")->getField('zid',true);
        /*循环获取用户的每个症状分数*/
        $map['uid'] = array('eq',$uid);
        $zhengzhuang_score = M('zhengzhuang_score');
        $zhengzhuang = M('zhengzhuang');
        $data = array();
        foreach ($uZid as $val) {
            $map['zid'] = array('eq',$val);
            $uScore = $zhengzhuang_score->where($map)->order("create_time DESC")->limit(1)->find();
            $zName  = $zhengzhuang->where("id={$val}")->getField('name');
            $data[] = array(
                'zid'         => $uScore['zid'],
                'name'        => $zName,
                'score'       => $uScore['score'],
                'create_time' => $uScore['create_time']
            );
        }
        echo $this->suc($data);
    }
    

/**







*/

/**
*@param $ret 错误的状态码
*
*@return json错误信息
*/
    protected function err($ret = 1){
        return json_encode(array('msg'=>'error','ret'=>$ret,'data'=>''));
    }

/**
*@return json成功信息(不带分页)
*/
    protected function suc($data = ''){
        return json_encode(array('msg'=>'success','ret'=>0,'data'=>$data));
    }

/**
*@return json成功信息(带分页)
*/
    protected function sucp($pagenum = 1, $pagesize = 10, $totalcount = 0, $data = ''){
        if($totalcount == 0) return json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
        $totalpage = ceil($totalcount/$pagesize);   //计算总页数
        if($pagenum >= $totalpage){
            $hasNextPage = false;
        }else{
            $hasNextPage = true;
        }
        $datas = array(
            'pagedatas'=>$data,
            'totalcount'=>$totalcount,
            'totalpage'=>$totalpage,
            'pagenum'=>$pagenum,
            'pagesize'=>$pagesize,
            'hasNextPage'=>$hasNextPage
        );
        return json_encode(array('msg'=>'success','ret'=>0,'data'=>$datas));
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
*@param $zid  症状的id
*@return 有此症状的人数
*/
    protected function getUserCount($zid){
        $count = M('zhengzhuang_user')->where("zid={$zid}")->count();
        return $count;
    }


/**运动样式的接口(此接口值针对运动)
*@description 接口
*@return 
*/
    public function getInfo(){
        $category_id = I('category_id');
        $pagenum = I('pagenum',1);
        $pagesize = I('pagesize',10);
        $uid = I('uid');
        if(empty($uid)) exit(err(100));
        if(empty($category_id)) exit(err(100));
        /*判断是否是食补分类的id*/
        if($category_id == 49){
            $this->getShi($category_id,$uid,$pagenum,$pagesize);
        }

        $count = M('document')->where("category_id={$category_id} and status=1")->count();
        $field = array('id','title','name'=>'action','create_time');
        $list = M('document')->field($field)->where("category_id={$category_id} and status=1")->order('update_time DESC')->page($pagenum,$pagesize)->select();
        foreach ($list as &$v) {
            $v['count'] = $this->getYunCount($v['id']);
            $v['score'] = $this->scores($v['action']);
            $v['isWan'] = $this->isWan($uid,$v['id']);
        }
        echo sucp($pagenum,$pagesize,$count,$list);
    }

// 运动完成提交的接口
    public function doYundong(){
        $yid = I('id');
        $uid = I('uid');
        $action = I('action');
        if(empty($yid) || empty($uid) || empty($action)) exit(err(100));
        $data = array(
            'yid'=>$yid,
            'uid'=>$uid,
            'create_time'=>$_SERVER['REQUEST_TIME']
        );
        /*判断这项运动今天是否完成过*/
        $map['yid'] = array('EQ',$yid);
        $map['uid'] = array('EQ',$uid);
        $map['create_time'] = array('GT',strtotime(date('Ymd')));
        if(M('yundong_user')->where($map)->find()) exit(err(200));/*完成了就结束*/
        $result = M('yundong_user')->add($data);
        if($result){
            action_log($action,'yundong_user',$result,$uid);
            echo suc();
        }else{
            echo err();
        }
    }
/*获取食补判断今天是否吃过*/
    public function getShi($category_id,$uid,$pagenum=1,$pagesize=10){
        /*$category_id = I('category_id');
        $pagenum     = I('pagenum',1);
        $pagesize    = I('pagesize',10);
        $uid         = I('uid');
        if(empty($uid)) exit(err(100));
        if(empty($category_id)) exit(err(100));*/
        $field = array('id','title','name'=>'action','create_time');
        $document = M('Document');
        /*判断是否是食补分类的id*/


        $count = $document->where("category_id={$category_id} and status=1")->count();
        
        $list = $document->field($field)->where("category_id={$category_id} and status=1")->order('update_time DESC')->page($pagenum,$pagesize)->select();
        $sModel = D('ShibuUser');
        foreach ($list as &$v) {
            $v['count'] = $sModel->getCount($v['id']);
            $v['score'] = $this->scores($v['action']);
            $v['isWan'] = $this->isWanShibu($uid,$v['id']);
        }
        exit( sucp($pagenum,$pagesize,$count,$list) );
    }

/*食补完成的接口*/
    public function doShibu(){
        $sid = I('id');
        $uid = I('uid');
        if(empty($sid) || empty($uid)) exit(err(100));
        $data = array(
            'uid' => $uid,
            'sid' => $sid,
            'create_time' => NOW_TIME,
        );
        /*判断这项运动今天是否完成过*/
        $map['sid'] = array('EQ',$sid);
        $map['uid'] = array('EQ',$uid);
        $map['create_time'] = array('GT',strtotime(date('Ymd')));
        if(M('shibu_user')->where($map)->find()) exit(err(200));/*完成了就结束*/

        if($result = M('shibu_user')->add($data)){
            action_log('add_shibu','shibu_user',$result,$uid);
            echo suc();
        }else{
            echo err();
        }
    }


// 获取文章图片的方法
    public function getImg($cover_id){
        $img = M('picture')->field('path')->where("id={$cover_id}")->find();
        return $img['path'];
    }


/**
*@param $id description : 运动的id 
*
*@return 参与此运动的人数
*/
    protected function getYunCount($id){
        $count = M('yundong_user')->where("yid={$id}")->count();
        return $count;
    }

/**
*@param $action description : 行为标识
*解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
*@return 完成此行为的积分
*/

    protected function scores($str){
        preg_match('/\d{1,2}/',$str,$result);
        return $result[0];
    }

    /*protected function scores($action){
        $info = M('action')->where("name='{$action}'")->find();
        $rules = $info['rule'];
        // $rules = str_replace('{$self}', $self, $rules);
        $rules = explode(';', $rules);
        $return = array();
        foreach ($rules as $key => &$rule) {
            $rule = explode('|', $rule);
            foreach ($rule as $k => $fields) {
                $field = empty($fields) ? array() : explode(':', $fields);
                if (!empty($field)) {
                    $return[$key][$field[0]] = $field[1];
                }
            }
        //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
            if (!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])) {
                unset($return[$key]['cycle'], $return[$key]['max']);
            }
        }
        $score = $return[0]['rule'];
        preg_match('/\d+/',$score,$scores);
        $scores = $scores[0];
        $result = $scores ? $scores : 0;

        return $result;
    }*/
/*判断用户的运动今天是否完成过了*/
    protected function isWan($uid,$zid){
        $map['create_time'] = array('gt',strtotime(date('Ymd')));
        $map['uid']         = array('eq',$uid);
        $map['yid']         = array('eq',$zid);
        $count = M('yundong_user')->where($map)->count();
        if($count > 0){
            return '1';
        }else{
            return '0';
        }
    }
/*判断用户今天食物是否吃过了*/
    protected function isWanShibu($uid,$sid){
        $map['create_time'] = array('gt',strtotime(date('Ymd')));
        $map['uid']         = array('eq',$uid);
        $map['sid']         = array('eq',$sid);
        $count = M('shibu_user')->where($map)->count();
        if($count > 0){
            return '1';
        }else{
            return '0';
        }
    }
/**
*获取用户每天的上脑次数 接口
*@param   $uid   用户的id
*/
    public function getJingCount(){
        $uid      = I('uid','','intval');
        $pagenum  = I('pagenum',1);
        $pagesize = I('pagesize',10);
        if(empty($uid)) exit( err(100) );
        $re = $this->getUserJingCount($uid,$pagenum,$pagesize);
        echo suc($re);
    }
/**
*跟据用户id获取用户每天的上脑次数
*@param   $uid  用户id
*/
    public function getUserJingCount($uid,$pagenum=1,$pagesize=10){
        if(empty($uid)) return '';
        $jingchong  = M('jingchong_record');
        $map['uid'] = $uid;
        $re = $jingchong->where($map)->order('create_time')->page($pagenum,$pagesize)->select();
        return $re ? $re : '';
    }
/**
*记录用户今日上脑的次数
*@param   $uid  用户id
*@return  void
*/
    public function todayRecordCount($uid){
        $record = M('jingchong_record');
        $first  = strtotime(date('Ymd',NOW_TIME));
        $last   = $first + 24*3600;
        $map['create_time'] = array('between',"{$first},{$last}");
        $map['uid']         = $uid;
        $re = $record->where($map)->find();
        if($re){
            $data = array(
                'id'          => $re['id'],
                'count'       => $re['count'] + 1,
                'create_time' => NOW_TIME,
            );
            $record->save($data);
        }else{
            $data = array(
                'count'       => 1,
                'create_time' => NOW_TIME,
                'uid'         => $uid,
            );
            $record->add($data);
        }
    }
/**
*新增举报   接口
*/
    public function addReport(){
        $re = D('Report')->addData($_POST);
        if($re){
            echo suc();
        }else{
            echo err();
        }
    }
/**
*戒撸历程接口（ 上脑信息 ）
*/
    public function getShang(){
        $uid = I('uid','','intval');
        if(empty($uid)) exit( err(100) );
        $pagenum  = I('pagenum',1);
        $pagesize = I('pagesize',10);
        $map['uid'] = $uid;
        $map['content'] = array('neq','');
        $jing_check = M('jing_check');
        $count = $jing_check->where($map)->count();
        $list = $jing_check->where($map)->order('create_time DESC')->page($pagenum,$pagesize)->select();
        echo sucp($pagenum,$pagesize,$count,$list?$list:'');
    }

/**
*Android版本更新接口
*/
    public function androidUpdate(){
        $data = C('VERSION_UPDATE_ANDROID');
        echo suc($data);
    }
/**
*IOS版本更新
*/
    public function iosUpdate(){
        $data = C('VERSION_UPDATE_IOS');
        echo suc($data);
    }
}











