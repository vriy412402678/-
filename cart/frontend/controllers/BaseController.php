<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17
 * Time: 18:59
 */

namespace frontend\controllers;
use Yii;
use common\controllers\ComController;
use yii\web\BadRequestHttpException;

class BaseController extends ComController
{


    public function beforeAction($action){

        if (parent::beforeAction($action)) {
            return true;
        }

    }







}