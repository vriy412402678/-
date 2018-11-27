<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17
 * Time: 18:55
 */

namespace frontend\modules\home\controllers;
use Yii;
use frontend\controllers\BaseController;
use yii\data\Pagination;
use yii\helpers\Html;

class IndexController extends BaseController
{
    public $enableCsrfValidation = false;

    public function beforeAction($action){
        parent::beforeAction($action);
        return true;
    }


    /**
     * 首页
     * @return string
     */
    public function actionIndex(){
        echo 1234;
    }


    
}