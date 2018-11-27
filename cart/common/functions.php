<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 11:53
 */

use yii\data\Pagination;
use yii\widgets\LinkPager;
use common\widgets\LinkPagerNew;
use common\controllers\ChuanglanSmsApi;

/**
 * Url跳转
 * @param $params
 * @return string
 */
function htmlUrl($params){
    return \yii\helpers\Url::toRoute($params,true);
}


/**
 * ajax return
 * @param int $error_code
 * @param string $msg
 * @param null $info
 */
function ajaxReturn($error_code = 1,$msg = '',$info = null){
    $data['error_code'] = $error_code;
    $data['msg']  = $msg;
    $data['data'] = $info;
    exit(json_encode($data));
}


/**
 * 校验密码格式是否合法 (密码必须以字母开头，长度在6~18之间，只能包含字符、数字和下划线)
 * @param $password
 * @return bool
 */
function is_pwd($password){
    if(preg_match("/^[a-zA-Z]\w{5,17}$/", $password)){
        return true;
    }else{
        return false;
    }
}




/**
 * 校验邮箱格式是否合法
 * @param $email
 * @return bool
 */
function validate_email($email)
{
    if (!preg_match('/^(?:[a-z\d]+[_\-\+\.]?)*[a-z\d]+@(?:([a-z\d]+\-?)*[a-z\d]+\.)+([a-z]{2,})+$/i', $email)) {
        return false;
    } else {
        return true;
    }
}



/**
 * 分页公用方法 （后端）
 * @params $data为要进行分页的数据  $pagesize为分页的页数
 * $type 为false输出数组，true输出对象 $arr为分页传入的参数（做查询时可以用到），默认为空
 * $firstPage 是否显示首页(true,false)，默认为false     $lastPage 是否显示尾页(true,false)，默认为false
 * $goPage 是否显示自定义跳转(true,false)，默认为false  $goButton 跳转按钮上的文字   $goPageLinkOptions自定义跳转表单的属性(如['class'=>'go-page'])
 **/
function pages($data,$pagesize=20,$type=false,$arr = [],$showPageSize=false,$maxButtonCount=10,$firstPage='首页',$lastPage='尾页',$goPage=true,$goButton='跳转',$goPageLinkOptions=[]){
    $pagecount = 1;//总页数
    if(is_array($data)){
        $models = [];
        foreach($data as $v){
            if(!empty($v)){
                $models[] = $v;
            }
        }
        $page = Yii::$app->request->get('page',Yii::$app->request->post('page',1));
        $pages = new Pagination(['totalCount' =>count($models),'pageSize' => $pagesize]);
        //限制输入超过分页最大数
        if($page > $pages->getPageCount()){
            $page = $pages->getPageCount();
        }
        if($page < 1){
            $page = 1;
        }
        $pages->setPage($page-1);
        //当前分页
        //$page = Yii::$app->request->get('page',Yii::$app->request->post('page',1));
        //计算取开始下标和结束下标
        $oneMu = $page*$pagesize - $pagesize;
        $endMu = ($page*$pagesize - $pagesize)+$pagesize - 1;
        $pagecount = $pages->getPageCount();

        $model = array();
        foreach($models as $key => $v ){
            if($oneMu <= $key && $key <= $endMu){
                $model[] = $v;
            }
        }
    }else{

        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => $pagesize]);
        if($type){
            $model = $data->offset($pages->offset)->limit($pages->limit)->all();
        }else{
            $model = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        }
        $pagecount = $pages->getPageCount();
    }

    if(!empty($arr)){
        $pages->params = $arr;
    }

    $widget = [
        'pagination' => $pages,
        'firstPageLabel' => $firstPage,
        'lastPageLabel' => $lastPage,
        'goPageLabel' => $goPage,
        'goButtonLable' => $goButton,
        'goPageLinkOptions' => $goPageLinkOptions,
        'maxButtonCount' => $maxButtonCount,
        'showPageSize' => $showPageSize
    ];

    if($pages->getPageCount()==1){
        $widget['hideOnSinglePage'] = false;
        $widget['internalPage'] = true;
        $widget['firstPageLabel'] = $firstPage;
        $widget['lastPageLabel'] = $lastPage;
        $widget['goPageLabel'] = true;
        $widget['showPageSize'] = $showPageSize;
    }

    return [
        'model' => $model,
        'no'   => ($pages->offset+1),
        'pages' => LinkPagerNew::widget($widget),
        'obj'  => $pages,
        'page_count' => $pagecount
    ];
}


/**
 * 分页公用方法 （前端）
 * @param $data
 * @param int $pagesize
 * @param bool $type
 * @param array $arr
 * @param bool $showPageSize
 * @param int $maxButtonCount
 * @return array
 * @throws Exception
 */
function front_pages($data,$pagesize=20,$type=false,$arr = [],$showPageSize=false,$maxButtonCount=10){

    $pagecount = 1;//总页数
    if(is_array($data)){
        $models = [];
        foreach($data as $v){
            if(!empty($v)){
                $models[] = $v;
            }
        }
        $page = Yii::$app->request->get('page',Yii::$app->request->post('page',1));
        $pages = new Pagination(['totalCount' =>count($models),'pageSize' => $pagesize]);
        //限制输入超过分页最大数
        if($page > $pages->getPageCount()){
            $page = $pages->getPageCount();
        }
        if($page < 1){
            $page = 1;
        }
        $pages->setPage($page-1);
        //当前分页
        //$page = Yii::$app->request->get('page',Yii::$app->request->post('page',1));
        //计算取开始下标和结束下标
        $oneMu = $page*$pagesize - $pagesize;
        $endMu = ($page*$pagesize - $pagesize)+$pagesize - 1;
        $pagecount = $pages->getPageCount();

        $model = array();
        foreach($models as $key => $v ){
            if($oneMu <= $key && $key <= $endMu){
                $model[] = $v;
            }
        }
    }else{

        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => $pagesize]);
        if($type){
            $model = $data->offset($pages->offset)->limit($pages->limit)->all();
        }else{
            $model = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        }
        $pagecount = $pages->getPageCount();
    }

    if(!empty($arr)){
        $pages->params = $arr;
    }

    $widget = [
        'pagination' => $pages,
        'maxButtonCount' => $maxButtonCount,
        'showPageSize' => $showPageSize
    ];

    if($pages->getPageCount()==1){
        $widget['hideOnSinglePage'] = false;
        $widget['internalPage'] = true;
        $widget['goPageLabel'] = false;
        $widget['showPageSize'] = $showPageSize;
    }

    return [
        'model' => $model,
        'no'   => ($pages->offset+1),
        'pages' => LinkPagerNew::widget([
            'pagination' => $pages,
        ]),
        'obj'  => $pages,
        'page_count' => $pagecount
    ];
}

/**
 *家谱树的应用 ,如面包屑导航 首页 > 手机类型 > CDMA手机 >诺基亚N9
 */

function familytree($arr,$id) {
    $tree = array();

    foreach($arr as $v) {

        if($v['id'] == $id) {// 判断要不要找父栏目
            if($v['parent_id'] > 0) { // parnet>0,说明有父栏目
                $tree = array_merge($tree,familytree($arr,$v['parent_id']));
            }

            $tree[] = $v;
        }
    }
    return $tree;
}

/**
 * 生成分类树
 * @param $data
 * @param int $pid
 * @param int $level
 * @return array
 */
function Tree($data,$pid=0,$level=1){
    $tree = [];
    if($data && is_array($data)){
        foreach($data as $v){
            if($v['pid'] == $pid){
                $tree[] = [
                    'name'  => $v['name'],
                    'url'   => $v['url'],
                    'id'    => $v['id'],
                    'pid'   => $v['pid'],
                    'level' => $level,
                    'child' => Tree($data,$v['id'],$level+1),
                ];
            }
        }
    }
    return $tree;
}


/**
 * 按要求封装数据，变成下拉列表
 * @data 数据集
 * @selected  0
 * @str null
 * @cstr null
 */
function treeSelect($data, $selected = 0,$str = null, $cstr = ''){

    if(!empty($data)){
        foreach ( $data as  $val ) {
            $temp = null;
            if(!isset($val['id'])){
                return $str;
            }
            if($val['id'] == $selected){
                $temp = "selected='selected'";
            }

            $str .= "<option value='".$val['id']."' $temp  >".$cstr.$val ['cat_name'].'</option>';
            if(!empty($val['child'])){
                $str .= treeSelect($val['child'], $selected, null, $cstr.'&nbsp&nbsp&nbsp&nbsp');
            }
        }
    }
    return $str;

}

/**
 * 获取图片路径
 * @param string $src
 * @return string
 */
function getImgSrc($src=""){
    if(!empty($src)){
        $imgUrl = Yii::$app->params['server_url'].'public/uploads/'.$src;
    }else{
        $imgUrl = '';
    }
    return $imgUrl;
}

/**
 * 发送短信
 * @param $data
 * @return array
 */
function smsSend($data){

    $clapi = new ChuanglanSmsApi();
    $code  = generate_code(5);
    $msg   = !empty($data['msg']) ? $data['msg'] : '【布色】您好, 您的验证码 ';

    switch($data['type']){
        case 1: //注册
            $hasPhone = CoolMember::find()->where(['phone'=>$data['phone']])->asArray()->One();

            if($hasPhone){
                return ['error_code'=>1,'msg'=>'该手机号码已注册'];exit;
            }

            //设置您要发送的内容：其中“【】”中括号为运营商签名符号，多签名内容前置添加提交
            $result = $clapi->sendSMS($data['phone'],$msg.$code);
            if(!is_null(json_decode($result))){

                $output=json_decode($result,true);

                if(isset($output['code'])  && $output['code']=='0'){
                    //拼装数据记录
                    $log = [
                        'phone'=>$data['phone'],
                        'code' => $code
                    ];
                    CoolCodeLog::writelog($log);
                    return ['error_code'=>0,'msg'=>'短信发送成功,请注意查收'];
                }else{
                    return ['error_code'=>1,'msg'=>$output['errorMsg']];
                }
            }else{
                return ['error_code'=>1,'msg'=>$result];
            }

            break;
        case 2: //忘记密码
            $hasPhone = CoolMember::find()->where(['phone'=>$data['phone']])->asArray()->One();

            if(!$hasPhone){
                return ['error_code'=>1,'msg'=>'该手机号码未注册'];exit;
            }
            //设置您要发送的内容：其中“【】”中括号为运营商签名符号，多签名内容前置添加提交
            $result = $clapi->sendSMS($data['phone'],$msg.$code);
            if(!is_null(json_decode($result))){

                $output=json_decode($result,true);

                if(isset($output['code'])  && $output['code']=='0'){
                    //拼装数据记录
                    $log = [
                        'phone'=>$data['phone'],
                        'code' => $code
                    ];
                    CoolCodeLog::writelog($log);
                    return ['error_code'=>0,'msg'=>'短信发送成功,请注意查收'];
                }else{
                    return ['error_code'=>1,'msg'=>$output['errorMsg']];
                }
            }else{
                return ['error_code'=>1,'msg'=>$result];
            }

            break;
        default:
            return ['error_code'=>1,'msg'=>'暂无可用类型'];
    }






}

/**
 * 生成固定的随机数
 * @param int $length
 * @return int
 */
function generate_code($length = 4) {
    $min = pow(10 , ($length - 1));
    $max = pow(10, $length) - 1;
    return rand($min, $max);
}


/**
 * 检验手机号
 * @param $phone
 * @return bool
 */
function is_phone($phone){
    if(preg_match("/^1[345678]{1}\d{9}$/",$phone)){
        return true;
    }else{
        return false;
    }
}

/**
 * 校验验证码
 * @param $phone
 * @param $code
 * @return array
 */
function verifiy_code($phone,$code){
    $info = CoolCodeLog::verifiyCode($phone,$code);
    return $info;
}


/**
 * 获取七牛视频外链
 * @param $url
 * @return string
 */
function getVideoSrc($url){
    if(!empty($url)){
        $videoUrl = Yii::$app->params['qiniu_external_links'].$url;
    }else{
        $videoUrl = '';
    }
    return $videoUrl;
}

