<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17
 * Time: 18:30
 */
namespace common\controllers;

use Yii;
use yii\web\Controller;


class ComController extends Controller
{

    public function beforeAction($action){

        parent::beforeAction($action);
        $this->assign('fstatic_dir',Yii::$app->params['fstatic_dir']);
        $this->assign('bstatic_dir',Yii::$app->params['bstatic_dir']);
        return true;
    }

    /**
     * 模版参数
     * @var array
     */
    public $tpl_vars = array();

    /**
     * 给模板赋值
     * @param $key
     * @param $var
     */
    public function assign($key,$var=null){
        if(is_array($key)){
            $this->tpl_vars = array_merge($this->tpl_vars,$key);

        }
        if(is_string($key)){
            $this->tpl_vars[$key] = $var;
        }
    }

    /**
     * 调用模板并赋值
     * @param $view
     * @param array $params
     * @return string
     */
    public function display($view, $params = [])
    {
        $params = array_merge($params,$this->tpl_vars);
        return $this->getView()->render($view, $params, $this);
    }

    /**
     * 弹出错误框
     * @param string $msg
     * @param string $url
     */
    public function ShowError($msg='',$url=''){
        $js  = '<script src="/public/backend/js/jquery.min.js"></script>';
        $js .= '<script src="/public/backend/js/layer/layer.js"></script>';
        $js .= '<script>layer.msg("'.$msg.'", {time:2000,icon:5});</script>';
        echo $js;
    }

}
