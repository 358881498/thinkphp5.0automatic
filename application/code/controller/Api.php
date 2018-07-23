<?php
/**
 * Created by PhpStorm.
 * User: YCH
 * Date: 2018/7/23
 * Time: 14:00
 */
namespace app\code\controller;
use Dome;
class Api extends Base
{
    public function sendSms($phone = null, $templateParam = array())
    {
        $phone = '';
        //模板参数，你可以在这里保存在缓存或者cookie等设置有效期以便逻辑发送后用户使用后的逻辑处理
        $code = 123456;
        $templateParam = array("code" => $code);
        $sms = new \dome\Aliyunsms();
        $m = $sms->send($phone,$templateParam);
        //类中有说明，默认返回的数组格式，如果需要json，在自行修改类，或者在这里将$m转换后在输出
        print_r($m);
//        return $m;
    }
    //阿里云oss
    public function oss()
    {
        if(!empty($_FILES['img'])){
            $oss = new \dome\Oss();
            $img = $oss->ossImg($_FILES['img'],'ossimg',0);
            print_r($img);
        }
    }
    //导出Excel
    public function exportExcel(){
        $name = '导出Excel';
        $header  = ['姓名', '性别', '生日',];
        $data[] = [
            'name' => '规范的1',
            'sex' => '规范的2',
            'birthday' => '规范的3',
        ];
        $export = new \dome\Excel();
        $export->export($name, $header,$data);
    }
}