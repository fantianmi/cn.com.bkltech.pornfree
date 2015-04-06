<?php
namespace Common\Model;
use Think\Model;

class BaiTuiModel extends Model
{
	//请开发者设置自己的apiKey与secretKey
	// protected $apikey     = C('APIKEY');
	// protected $secret_key = C('SECRET_KEY');

	/*$timestamp : 用户发起请求时的unix时间戳。本次请求签名的有效时间为该时间戳+10分钟。*/
	// protected $timestamp  = NOW_TIME + 600;

/**
*@var      推送消息给所有人
*@param    $apikey        百度获取apikey
*@param    $secret_key    百度获取secret_key
*@param    $title         要推送的标题
*@param    $description   推送的内容
*@return   json           百度页面返回的结果
*/
	public function genDataAll($title='',$description=''){
		$method     = 'push_msg';//API的资源操作方法名
		$url        = 'http://channel.api.duapp.com/rest/2.0/channel/channel';
		$push_type  = 3;
		$message_type = 1;
		$messages   = json_encode(array('title'=>$title,'description'=>$description));
		$msg_keys   = 'testkey';//消息标识
		$arrContent = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'push_type'  => $push_type,
			'messages'   => $messages,
			'msg_keys'   => $msg_keys,
			'message_type' => $message_type,
			'timestamp'  => $this->timestamp,

		);
		$sign       = $this->genSign($this->secret_key,'POST',$url,$arrContent);
		$data = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'push_type'  => $push_type,
			'messages'   => $messages,
			'msg_keys'   => $msg_keys,
			'timestamp'  => $this->timestamp,
			'message_type' => $message_type,
			'sign'       => $sign,
		);
		$return = $this->curlPost($data,$url);
		return $return;
	}
/**
*推送消息给一个人 （ 一对一 ）
*@return   json 百度页面返回的结果
*/
	public function genDataOne($uid,$title='',$description='',$uids=''){
		// $path = __ROOT__.'/ThinkPHP/Library/Vendor/Baidu/sample/sample.php';
		// require_once($path);
		$info       = D('Member')->getBaiUser($uid);
		$user_id    = $info['user_id'];
		$channel_id = $info['channel_id'];
		if($info['bType'] == 'ios'){
			$re = $this->pushMessage_ios($user_id,$title,$description,$uids);
			return $re;
		}else if($info['bType'] == 'android'){
//			Vendor('Baidu.sample.sample');
			$re = $this->pushMessage_android($user_id,$title,$description,$uids);
			$this->pushMessage_android_tou($user_id,$title,$description,$uids);
			return $re;
		}
	}


/**
 * ios的推送
 */
	public function pushMessage_ios($user_id,$title,$description,$type){
		if(!$user_id) return '';
		$apiKey    = C('APIKEY');
		$messages = json_encode(array(
				'title'       => $title,
				'description' => $description,
        		'aps'         => array(
        			'alert'   => $title,
        			'sound'   => 'www.bkltech.pornfree',
        			'badge'   => 0
        		),
        		"type"        => $type
        	));
       
    	$data = array(
    		'method'         =>   'push_msg',
    		'apikey'         =>   $apiKey,
    		'user_id'        =>   $user_id,
    		'push_type'      =>   1,
    		// 'channel_id'     =>   '4819124169066184786',
    		'device_type'    =>   4,
    		'message_type'   =>   1,
    		'messages'       =>   $messages,
    		'msg_keys'       =>   'testkey',
    		'deploy_status'  =>   2,//1：开发状态 2：生产状态
    		'timestamp'      =>   NOW_TIME + 600,

    	);
    	$re = $this->sign($data);
    	$url = 'http://channel.api.duapp.com/rest/2.0/channel/channel';
    	$result = $this->curlPost($re,$url);
    	// dump($result);
    	return $result;
	}

/**
 * android的推送
 */
    public function pushMessage_android($user_id,$title,$description,$type){
        if(!$user_id) return '';
        $apiKey    = C('APIKEY');
        $messages = json_encode(array(
            'title'          => $title,
            'description'    => $description,
            'custom_content' => array('type'=>$type)
        ));

        $data = array(
            'method'         =>   'push_msg',
            'apikey'         =>   $apiKey,
            'user_id'        =>   $user_id,
            'push_type'      =>   1,
            // 'channel_id'     =>   '4819124169066184786',
            'device_type'    =>   3,
            'message_type'   =>   1,
            'messages'       =>   $messages,
            'msg_keys'       =>   'testkey',
            'deploy_status'  =>   2,
            'timestamp'      =>   NOW_TIME + 600,

        );
        $re = $this->sign($data);
        $url = 'http://channel.api.duapp.com/rest/2.0/channel/channel';
        $result = $this->curlPost($re,$url);
        // dump($result);
        return $result;
    }

/**
 * android的透传
 */
    public function pushMessage_android_tou($user_id,$title,$description,$type){
        if(!$user_id) return '';
        $apiKey    = C('APIKEY');
        $messages = json_encode(array(
            'title'          => $title,
            'description'    => $description,
            'custom_content' => array('type'=>$type)
        ));

        $data = array(
            'method'         =>   'push_msg',
            'apikey'         =>   $apiKey,
            'user_id'        =>   $user_id,
            'push_type'      =>   1,
            // 'channel_id'     =>   '4819124169066184786',
            'device_type'    =>   3, //指定为android设备
            'message_type'   =>   0,//指定类型为透传
            'messages'       =>   $messages,
            'msg_keys'       =>   'testkey',
            'deploy_status'  =>   2,
            'timestamp'      =>   NOW_TIME + 600,
        );
        $re = $this->sign($data);
        $url = 'http://channel.api.duapp.com/rest/2.0/channel/channel';
        $result = $this->curlPost($re,$url);
        // dump($result);
        return $result;
    }




    /**
 * 生成签名
 */
	public function sign($arrContent){
		$secretKey = C('SECRET_KEY');
    	$gather    = 'POSThttp://channel.api.duapp.com/rest/2.0/channel/channel';
	    ksort($arrContent);
	    foreach($arrContent as $key => $value) {
	        $gather .= $key.'='.$value;
	    }   
	    $gather .= $secretKey;
	    $sign    = md5(urlencode($gather));
	    $arrContent['sign'] = $sign;
	    return $arrContent;
    }
/**
 * curl抓取消息
 * @param  [type] $data [description]
 * @param  [type] $url  [description]
 */
    protected function curlPost($data,$url){
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_FILETIME, true );
		curl_setopt ( $ch, CURLOPT_FRESH_CONNECT, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, true );
		curl_setopt ( $ch, CURLOPT_CLOSEPOLICY, CURLCLOSEPOLICY_LEAST_RECENTLY_USED );
		curl_setopt ( $ch, CURLOPT_MAXREDIRS, 5 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 5184000 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt ( $ch, CURLOPT_NOSIGNAL, true );
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		return $return;
	}















/**
*@var      推送消息给一群人 （ 一对多 推的tag标签 ）
*@param    $tag           推送消息的标签
*@return   json           百度页面返回的结果
*/
	public function genDataMany($tag,$title='',$description=''){
		if(empty($tag)) exit( '缺少参数' );
		$method     = 'push_msg';//API的资源操作方法名
		$url        = 'http://channel.api.duapp.com/rest/2.0/channel/channel';
		$push_type  = 2;
		$message_type = 1;
		$messages   = json_encode(array('title'=>$title,'description'=>$description));
		$msg_keys   = 'testkey';//消息标识
		$arrContent = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'push_type'  => $push_type,
			'messages'   => $messages,
			'msg_keys'   => $msg_keys,
			'timestamp'  => $this->timestamp,
			'tag'        => $tag,
			'message_type' => $message_type
		);
		$sign       = $this->genSign($this->secret_key,'POST',$url,$arrContent);
		$data = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'push_type'  => $push_type,
			'messages'   => $messages,
			'msg_keys'   => $msg_keys,
			'timestamp'  => $this->timestamp,
			'tag'        => $tag,
			'message_type' => $message_type,
			'sign'       => $sign,
		);
		$return = $this->curlPost($data,$url);
		return $return;
	}
/**
*@var      服务器端设置用户标签。当该标签不存在时，服务端将会创建该标签。特别地，当user_id被提交时，服务端将会完成用户和tag的绑定操作。
*@param    $tag           设置的标签名
*@param    $user_id       需要绑定此标签的用户id
*@return   json           百度页面返回的结果
*/
	public function setTag($tag,$user_id){
		if(empty($tag)) exit( '缺少参数' );
		$method     = 'set_tag';//API的资源操作方法名
		$url        = 'https://channel.api.duapp.com/rest/2.0/channel/channel';
		$arrContent = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'tag'        => $tag,
			'user_id'    => $user_id,
			'timestamp'  => $this->timestamp,
		);
		$sign       = $this->genSign($this->secret_key,'POST',$url,$arrContent);
		$data = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'tag'        => $tag,
			'timestamp'  => $this->timestamp,
			'user_id'    => $user_id,
			'sign'       => $sign,
		);
		$return = $this->curlPost($data,$url);
		return $return;
	}
/**
*@var 服务端删除用户标签。特别地，当user_id被提交时，服务端将只会完成解除该用户与tag绑定关系的操作。
*@param    $tag           推送消息的标签
*@param    $user_id       用户id（ 防止标签被删除，设置$user_id为必传参数 ）
*@return   json           百度页面返回的结果
*/
	public function deleteTag($tag,$user_id){
		if(empty($tag) || empty($user_id)) exit( '缺少参数' );
		$method     = 'delete_tag';//API的资源操作方法名
		$url        = 'https://channel.api.duapp.com/rest/2.0/channel/channel';
		$arrContent = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'tag'        => $tag,
			'user_id'    => $user_id,
			'timestamp'  => $this->timestamp,
		);
		$sign       = $this->genSign($this->secret_key,'POST',$url,$arrContent);
		$data = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'tag'        => $tag,
			'timestamp'  => $this->timestamp,
			'user_id'    => $user_id,
			'sign'       => $sign,
		);
		$return = $this->curlPost($data,$url);
		return $return;
	}
/**
*@var      查询用户所属的标签列表
*@param    $tag           推送消息的标签
*@return   json           百度页面返回的结果
*/
	public function queryUserTags($user_id){
		if(empty($user_id)) exit( '缺少参数' );
		$method     = 'query_user_tags';//API的资源操作方法名
		$url        = 'https://channel.api.duapp.com/rest/2.0/channel/channel';
		$arrContent = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'user_id'    => $user_id,
			'timestamp'  => $this->timestamp,
		);
		$sign       = $this->genSign($this->secret_key,'POST',$url,$arrContent);
		$data = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'timestamp'  => $this->timestamp,
			'user_id'    => $user_id,
			'sign'       => $sign,
		);
		$return = $this->curlPost($data,$url);
		return $return;
	}
/**
*@var      查询应用的标签
*@param    $tag           推送消息的标签
*@return   json           百度页面返回的结果
*/
	public function fetchTag($tag=''){
		$method     = 'fetch_tag';//API的资源操作方法名
		$url        = 'https://channel.api.duapp.com/rest/2.0/channel/channel';
		if(empty($tag)){
			$arrContent  = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'timestamp'  => $this->timestamp,
			);
			$sign       = $this->genSign($this->secret_key,'POST',$url,$arrContent);
			$data = array(
				'method'     => $method,
				'apikey'     => $this->apikey,
				'timestamp'  => $this->timestamp,
				'sign'       => $sign,
			);
			$return = $this->curlPost($data,$url);
			return $return;
		}else{
			$arrContent  = array(
			'method'     => $method,
			'apikey'     => $this->apikey,
			'tag'        => $tag,
			'timestamp'  => $this->timestamp,
			);
			$sign       = $this->genSign($this->secret_key,'POST',$url,$arrContent);
			$data = array(
				'method'     => $method,
				'apikey'     => $this->apikey,
				'tag'        => $tag,
				'timestamp'  => $this->timestamp,
				'sign'       => $sign,
			);
			$return = $this->curlPost($data,$url);
			return $return;
		}
	}


/**
隔断 ，下面是公用的方法
*/


/**
*@var      生成sign签名
*@param    $secret_key    百度获取secret_key
*@param    $method        http请求方式 GET 或者 POST
*@param    $url           请求的地址
*@param    $arrContent    提交的所有参数
*@return   string         签名字符串
*/
	protected function genSign($secret_key, $method, $url, $arrContent) {
	    $gather = $method.$url;
	    ksort($arrContent);
	    foreach($arrContent as $key => $value) {
	        $gather .= $key.'='.$value;
	    }   
	    $gather .= $secret_key;
	    $sign = md5(urlencode($gather));
	    return $sign;
	}
/**
*@var      curl模拟post提交数据
*@param    $data          提交的所有参数
*@param    $url           请求的地址
*@return   json           百度页面返回的结果
*/
	/*protected function curlPost($data,$url){
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		return $return;
	}*/
}





