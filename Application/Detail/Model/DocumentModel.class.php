<?php
namespace Detail\Model;
use Think\Model;

class DocumentModel extends Model
{
/**
* 获取详情页数据
* @param   $id         文档ID
* @return  array       详细数据
*/
    public function detail($id){
	    $info = $this->field(true)->find($id);
	    /*添加用户的昵称添加到数组里*/
	    $info['nickname'] = D('Member')->getNickname($info['uid']);
	    /*获取封面图并添加到数字组*/
	    $info['path'] = D('Picture')->getPath($info['cover_id']);
	    /*取得上一篇和下一篇的id并添加到数组*/
	    $info['prev'] = $this->prev($info);
	    $info['next'] = $this->next($info);

	    if(!(is_array($info)) || 1 != $info['status']){
	        return false;
	    }
	    $model_name = $this->getModelName($info['model_id']);
	    $detail     = $this->getModelDetail($model_name,$id);
	    // 更新浏览次数
	    $this->setView($id);
	    
	    return array_merge($info,$detail);
	}
/**
*获取模型的名称( 用的地方不多，我就没有单独写个文件来调用了 )
*@param    $mdoel_id   模型的id
*@return   string      模型的名字
*/
	public function getModelName($model_id){
		$model_name = M('model')->where("id={$model_id}")->getField('name');
		if($model_name){
			return 'document_'.$model_name;
		}else{
			return '';
		}
	}
/**
*获取核心模型的详情
*@param    $models     模型名
*@param    $id         模型的id
*@param    array       详情数组
*/
	public function getModelDetail($models,$id){
		$model = M($models);
		$data  = $model->where('id='.$id)->find();
		return $data ? $data : '';
	}

/**
* 返回前一篇文档信息
* @param  array $info 当前文档信息
* @return array
*/
    public function prev($info){
        $map = array(
            'id'          => array('lt', $info['id']),
            'pid'		  => 0,
            'category_id' => $info['category_id'],
            'status'      => 1,
            'create_time' => array('lt', NOW_TIME),
            '_string'     => 'deadline = 0 OR deadline > ' . NOW_TIME,  			
        );

        /* 返回前一条数据 */
        $result = $this->field('id,title')->where($map)->order('id DESC')->find();
        return $result ? $result : '';
    }
/**
* 获取下一篇文档基本信息
* @param  array    $info 当前文档信息
* @return array
*/
    public function next($info){
        $map = array(
            'id'          => array('gt', $info['id']),
            'pid'		  => 0,
            'category_id' => $info['category_id'],
            'status'      => 1,
            'create_time' => array('lt', NOW_TIME),
            '_string'     => 'deadline = 0 OR deadline > ' . NOW_TIME,  			
        );

        /* 返回下一条数据 */
        $result = $this->field('id,title')->where($map)->order('id')->find();
        return $result ? $result : '';
    }
/**
 * 更新浏览次数
 * @param  int  $id   文章id
 */
	public function setView($id){
		$map['id'] = $id;
		$this->where($map)->setInc('view');
	}
}


