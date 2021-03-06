<?php

namespace Addons\demo;
use Common\Controller\Addon;

/**
 * 示列插件
 * @author where
 */

    class demoAddon extends Addon{

        public $info = array(
            'name'=>'demo',
            'title'=>'示列',
            'description'=>'我写来测试的',
            'status'=>1,
            'author'=>'where',
            'version'=>'0.1'
        );

        public $admin_list = array(
            'model'=>'Example',		//要查的表
			'fields'=>'*',			//要查的字段
			'map'=>'',				//查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
			'order'=>'id desc',		//排序,
			'listKey'=>array( 		//这里定义的是除了id序号外的表格里字段显示的表头名
				'字段名'=>'表头显示名'
			),
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        public function demo($param){
            $arr = M('addons')->field("config")->where('name="ImageSlider"')->find();
            $arr = json_decode($arr['config'],true);
            $url = $arr['url'];
            $url = explode(",",$url);
            $where = $arr['images'];
            $image = M('picture')->field("id,path")->where("id in ($where)")->order('id desc')->select();
            $i = 0;
            foreach ($image as &$v) {
                $v['url'] = $url[$i];
                $i++;
            }
            // dump($image);
            echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$image));
        }
        //实现的documentDetailAfter钩子方法
        public function documentDetailAfter($param){
             // echo "dajslk";
        }

    }