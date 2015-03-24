<?php
namespace Face\Controller;
use Think\Controller;
// use Blog\Controller\FaceController;
// use Common\Controller\FaceController;
$path = dirname($_SERVER['SCRIPT_FILENAME']);
// require_once $path."/Application/Common/Controller/FaceController.class.php";
class DemoController extends Controller{
	protected function _initialize(){

        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

    }


	public function gets(){
		header("content-type:text/html;charset=utf-8");
		$user = M()->query("select count(uid),uid from thinkox_check_info group by uid");
		$u    = M('check_info')->group('uid')->count();
		dump($user);
		dump($u);
	}

	public function index(){
		$a = "/Uploads/Editor/20150107/54aca5df0f58e.jpg";
		$path = dirname($_SERVER['SCRIPT_FILENAME']).$a;
		echo $path;
		$image = new \Think\Image(); 
		$image->open($path);
		$width = $image->width();
		$height = $image->height();
		$size = $image->size();
		dump($width);
		dump($height);
		dump($size);
		die;
		$pass = "md5";
		session('pass',$pass);
		// echo addons_url('Support://Support/doSupport');
		$this->display();
	}
	public function login(){
		echo session('pass');
		$this->display();
	}
	// start
	public function logins(){

	}
	// end
	public function edit(){
		session('pass',null);
		$this->display();
	}
	public function pass(){
		$path = dirname($_SERVER['SCRIPT_FILENAME']);
		require_once $path."/Application/User/Common/common.php";
		echo UC_AUTH_KEY;
		echo "<br/>";
		echo think_ucenter_md5('admin123','u+Sw98l%gWK4AZ#[ThQzex^,5ObV_tk("-N]viq7');
		/*$data = array(
			"username" =>"9887w8",
			"password" => "20d696e64376410c753399e522a6a0c8",
			  "email" => "dlsajjd",
			  "reg_time" => 1417768847,
			  "reg_ip" => "3232235649",
			  "update_time" => 1417768847,
			  "status" => 1,
			  "id" => 91
		);
		$re = M('ucenter_member')->add($data);
		dump($re);*/
		$this->display();
	}
	public function lists(){
		$this->display();
	}
	public function weibo(){
		$this->display();
	}
	public function weiboList(){
		$this->display();
	}
	public function pinglun(){
		$this->display();
	}
	public function huitie(){
		$this->display();
	}
	public function upload(){
		$this->display();
	}
	public function shouchang(){
		$this->display();
	}
	public function fatie(){
		$this->display();
	}
	public function ping(){
		$this->display();
	}
	public function zhan(){
		$this->display();
	}
	public function image(){
		hook('demo');
		/*echo "<br/>";
		hook('imageSlider');*/
	}
	public function qiandao(){
		$this->display();
	}
	public function files(){
		header("content-type:text/html;charset=utf-8");
		// echo "<pre>";
		$path = dirname($_SERVER['SCRIPT_FILENAME']);
		$re = include_once $path."/Addons/ImageSlider/config.php";
		echo $re;
		dump($re);
	}


	public function editor(){
		$str = <<<EOL
		<textarea name="text">
		</textarea>
EOL;
echo $str;
		hook('adminArticleEdit',array('name'=>'text','value'=>'text'));
	}
	// 用户添加症状
	public function zhengzhuang(){
		$this->display();
	}
	// 测试意见提交
	public function yijian(){
		$this->display();
	}

	public function shibu(){
		$this->display();
	}
	public function addReport(){
		header("content-type:text/html;charset=utf-8");
		$uid = 89;
		$data1 = M('check_info')->where("uid={$uid}")->select();
		$data2 = M('jingchong')->where("uid={$uid}")->select();
		$data = array_merge($data1,$data2);
		dump($data);


		die;
		$re = C('DOCUMENT_DISPLAY');
		dump($re);
		die;
		$a = C('CONFIG_TYPE_LIST');
		dump($a);
		die;
		$r = C('_USER_LEVEL');
		$a = explode('\r\n',$r);
		dump($a);
		die;
		// $ip  = '192.168.0.104';
		$ip  = '222.182.92.255';
		$arr = get_city_by_ip($ip);
		dump($arr);

		die;
		$this->display();
	}
}
/*die;
header("content-type:text/html;charset=utf-8");
$a="http://www.yangyutuan.cn/order/index.php?sign=973d13747810573699c6de902b316c9b&protocol=httpJson&orderNo=1641910432029081go-274-1-aqej&signType=MD5&resultCode=PARAM_FORMAT_ERROR&service=commonTradePay&partnerId=20150116020003762560&inlet=01&resultMessage=%E5%8F%82%E6%95%B0%E6%A0%BC%E5%BC%8F%E9%94%99%E8%AF%AF%5B%E5%8F%82%E6%95%B0goodsClauses%3D%5B%7B%5C%27name%5C%27%3A%5C%27%E4%BB%85%E5%94%AE16%E5%85%83%EF%BC%81%E4%BB%B7%E5%80%BC20%E5%85%83%E7%9A%84%E4%BB%A3%E9%87%91%E5%88%B81%E5%BC%A0%EF%BC%8C%E5%85%A8%E5%9C%BA%E9%80%9A%E7%94%A8%EF%BC%8C%E5%8F%AF%E5%8F%A0%E5%8A%A0%E4%BD%BF%E7%94%A8%E3%80%82%5C%27%2C%5C%27price%5C%27%3A%5C%2716.00%5C%27%2C%5C%27quantity%5C%27%3A%5C%271%5C%27%7D%5D%2C%E6%A0%BC%E5%BC%8F%E9%94%99%E8%AF%AF%2C%E9%9C%80%E8%A6%81%E7%9A%84%3AList%5D&success=false&version=1.0";
echo urldecode($a);*/
