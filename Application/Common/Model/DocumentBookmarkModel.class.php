<?php
namespace Common\Model;
use Think\Model;

class DocumentBookmarkModel extends Model
{
	/**
	 * 收藏方法
	 */
	public function bookmark($uid,$document_id){
		$data['uid']          = $uid;
		$data['document_id']  = $document_id;
		$data['create_time']  = NOW_TIME;
		return $this->add($data);
	}
	/**
	 * 取消收藏方法
	 * @param  [type] $uid         [description]
	 * @param  [type] $document_id [description]
	 * @return 
	 */
	public function unBookmark($uid,$document_id){
		$map['uid']         = $uid;
		$map['document_id'] = $document_id;
		return $this->where($map)->delete();
	}

/**
 * 用户获取收藏列表
 * @param  [type] $uid [description]
 */
	public function getList($uid,$pagenum=1,$pagesize=10,&$totalcount){
		// 先获取文章的id
		$totalcount = $this->where('uid='.$uid)->count();
		if($totalcount < 1) return '';//没有收藏直接返回

		// 判断是否有缓存
		$cache = S('article_book_' . $uid . '_' . $pagenum);
		if(!empty($cache)) return $cache;
		
		$id = $this->where('uid='.$uid)->order('create_time DESC')->page($pagenum,$pagesize)->getField('document_id',true);
		$map['d.id']     = array('in',implode(',',$id));
		$map['d.status'] = 1;
		$field = array('d.id','d.title','d.description','d.cover_id','d.link_id'=>'link','d.score','d.price','d.size','p.path');
		$list = D('Document d')->field($field)->where($map)->join('__PICTURE__ p ON d.cover_id=p.id','LEFT')->select();
		$return = $list ? $list : '';
		S('article_book_' . $uid . '_' . $pagenum,$return,60);
		return $return;
	}

	/**
	 * 检查是否收藏过
	 * @param  [type] $uid         [description]
	 * @param  [type] $document_id [description]
	 * @return [string]  1 收藏过了  0 没用收藏
	 */
	public function checkBook($uid,$document_id){
		if($uid < 1) return '0';
		$map['uid']         = $uid;
		$map['document_id'] = $document_id;
		return $this->where($map)->count();
	}
}