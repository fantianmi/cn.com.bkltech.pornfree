<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Blog\Controller;

use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends BlogController
{

    //系统首页
    public function index($page = 1)
    {

        /* 分类信息 */
        $category = 0; //$this->category();

        /* 获取当前分类列表 */
        $Document = D('Document');
        $list = $Document->page($page, 10)->lists($category['id']);
        if (false === $list) {
            $this->error('获取列表数据失败！');
        }



        /* 模板赋值并渲染模板 */
        // dump($list);
        $this->assign('category', $category);
        $this->assign('list', $list);

        $this->assign('page', D('Document')->page); //分页


        $this->display();
    }

    /* 文档分类检测 */
    private function category($id = 0)
    {

    }
    // my获取资讯的分类
    public function getCate(){
        $where = array(
            'status'=>1
        );
        $field = array('id','title','description','icon');
        $cate = M('category')->field($field)->where($where)->order("sort")->select();
        if($cate){
            foreach ($cate as &$v) {
                $v['path'] = $this->getIcon($v['icon']);
            }
            echo suc($cate);
        }else{
            echo err();
        }
    }
    // 配合上面的方法获取图片
    private function getIcon($icon){
        $where = array(
            'id'=>$icon,
            'type'=>'local',
            'status'=>1
        );
        $img = M('picture')->field(array('path'))->where($where)->find();
        $path = $img['path'] ? $img['path'] : "Uploads/Picture/default.png";
        return $path;
    }
    // 根据分类id获取此分类下的所有文章
    public function lists($category_id='',$pagesize=10,$pagenum=1){
        if($category_id == ''){
            exit(err(100));
        }
        $field = array('id','title','description','cover_id','link_id'=>'link','score','price','size');
        $where = array(
            'status'=>1,
            'category_id'=>$category_id
        );
        $data = M('document')->field($field)->where($where)->order('update_time DESC')->page($pagenum,$pagesize)->select();
        if ($data) {
            foreach ($data as &$v) {
                $v['path'] = $this->getImg($v['cover_id']);
            }
        }else{
            $data = '';
        }
        $count = M('document')->where($where)->count();
        $totalpage = ceil($count/$pagesize);
        if($pagenum >= $totalpage){
            $hasNextPage = false;
        }else{
            $hasNextPage = true;
        }
        $datas = array(
            "pagedatas"=>$data,
            'pagesize'=>$pagesize,
            'pagenum'=>$pagenum,
            'totalcount'=>$count,
            'totalpage'=>$totalpage,
            "hasNextPage"=>$hasNextPage
        );
        echo suc($datas);
    }
    // 配合上面的方法获取文章图片
    public function getImg($cover_id){
        $img = M('picture')->field('path')->where("id={$cover_id}")->find();
        $path = $img['path'] ? $img['path'] : "Uploads/Picture/default.png";
        return $path;
    }

}