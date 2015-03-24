<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/21
 * Time: 11:01
 */
namespace Inter\Controller;
use Think\Controller;


class DemoController extends Controller
{
    public function index(){
        $uid = I('uid');
        $zid = I('zid');
        if(empty($uid) || empty($zid))
            exit( err(100,'缺少参数') );
        $demo = D('Demo');
    }
}










