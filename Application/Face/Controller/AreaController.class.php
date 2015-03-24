<?php
namespace Face\Controller;
use Think\Controller;
class AreaController extends Controller{

	/*
	*	获取全部地区接口
	*	@param  id 以get的方式传入一个int的正整数
	*
	*/
	public function area(){
		// S('area',null);
		header("content-type:text/html;charset=utf-8");
		if(isset($_GET['id'])){
			$id = $_GET['id'];
		}else{
			$id = $_POST['id'];
		}
		if($id > 0){
			if(S('area')){
				// echo "缓存";
				echo json_encode(array('data'=>S('area'),'msg'=>'success','ret'=>0));
			}else{
				// echo "数据库";
				$area = M('district')->select();
				// dump(M('district'));
				echo json_encode(array('data'=>$area,'msg'=>'success','ret'=>0));
				S('area',$area,300);
			}
		}
	}
	// 系统地区钩子
	public function getArea(){
		hook('J_China_City',array());
		$str = <<<EOL
		<form action="demo.php">
		<input type="submit" value="提交"/>
		</form>
EOL;
	echo $str;
	}
}



