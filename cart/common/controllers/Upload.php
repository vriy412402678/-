<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27
 * Time: 12:27
 */

namespace common\controllers;

use Yii;
use yii\web\Controller;


class Upload
{
    public $file = ''; //文件name名称
    public $path = ''; //目录
    public $uploadPath; //上传路径
    public $allowExt = ['jpg', 'jpge', 'png', 'bmp', 'JPG', 'JPGE', 'PNG', 'BMP']; //允许上传的图片类型
    private $maxSize = 5242880; //最大允许上传大小5


    /**
     * 文件上传
     */
    public function UploadImg()
    {
        $isReal = true;
        $fileInfo = $_FILES[$this->file];
        if ($fileInfo['error'] > 0) {
            switch ($fileInfo['error']) {
                case 1:
                    $message = '上传文件超过了PHP配置文件中upload_max_filesize选项的值';
                    break;
                case 2:
                    $message = '超过了HTML表单MAX_FILE_SIZE限制的大小';
                    break;
                case 3:
                    $message = '文件部分被上传';
                    break;
                case 4:
                    $message = '没有选择上传文件';
                    break;
                case 6:
                    $message = '没有找到临时目录';
                    break;
                case 7:
                    $message = '文件写入失败';
                    break;
                case 8:
                    $message = '上传的文件被PHP扩展程序中断';
                    break;
            }
            ajaxReturn(1, $message);
        }

        $ext = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);

        //检测上传文件类型
        if (!in_array($ext, $this->allowExt)) {
            return ['error' => 1, 'msg' => '非法的文件类型'];
        }

        //检测上传文的件大小是否符合规范
        if ($fileInfo['size'] > $this->maxSize) {
            return ['error' => 1, 'msg' => '上传文件过大'];
        }

        //检测图片是否为真实的图片类型
        if ($isReal) {
            if (!getimagesize($fileInfo['tmp_name'])) {
                return ['error' => 1, 'msg' => '不是真实的图片类型'];
            }
        }

        //检测是否通过HTTP POST方式上传
        if (!is_uploaded_file($fileInfo['tmp_name'])) {
            return ['error' => 1, 'msg' => '文件不是通过HTTP POST方式上传'];
        }

        //如果没有这个文件夹,那么就创建一个
        if (!file_exists($this->uploadPath . $this->path)) {
            mkdir($this->uploadPath . $this->path, 0777, true);
            chmod($this->uploadPath . $this->path, 0777);
        }

        //文件名唯一,避免被覆盖
        $unquieName = md5(uniqid(microtime(true), true)) . '.' . $ext;
        $destiNation = $this->uploadPath . $this->path . '\\' . $unquieName;
//        var_dump($destiNation);exit;
        if (!@move_uploaded_file($fileInfo['tmp_name'], $destiNation)) {
            return ['error' => 1, 'msg' => '文件移动失败'];
        }

        return ['error' => 0, 'msg' => '文件上传成功', 'url' => $this->path . '/' . $unquieName];
    }






}