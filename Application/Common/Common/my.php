<?php


/**
*@param $ret 错误的状态码
*
*@return json错误信息
*/
    function err($ret = 1,$msg='error'){
        return json_encode(array('msg'=>$msg,'ret'=>$ret,'data'=>''));
    }

/**
*@return json成功信息(不带分页)
*/
    function suc($data = '',$msg='success'){
        return json_encode(array('msg'=>$msg,'ret'=>0,'data'=>$data));
    }

/**
*@return json成功信息(带分页)
*/
    function sucp($pagenum = 1, $pagesize = 10, $totalcount = 0, $data = '',$msg='success'){
        // if($totalcount == 0) return json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
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
        return json_encode(array('msg'=>$msg,'ret'=>0,'data'=>$datas));
    }
/**
 * 将数据组装成json分页的数据
 * @param  integer $pagenum    [description]
 * @param  integer $pagesize   [description]
 * @param  integer $totalcount [description]
 * @param  array   $data       [description]
 * @return array
 */
	function calculation($pagenum=1,$pagesize=10,$totalcount=0,$data=array()){
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
        return $datas;
	}





