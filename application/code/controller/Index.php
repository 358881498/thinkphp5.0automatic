<?php
/**
 * Created by PhpStorm.
 * User: YCH
 * Date: 2018/7/23
 * Time: 14:54
 */

namespace app\code\controller;
use app\index\Build;
use think\Loader;

class Index extends Base
{
    //首页
    public function index()
    {
        return view();
    }
    //公共文件页面
    public function common(){
        return view();
    }
    //配置文件
    public function extra(){
        return view();
    }
    //获取模块
    public function mokuai(){
        $dir = dirname(dirname(dirname(__FILE__)));
        $files = array();
        if(is_dir($dir)){
            $child_dirs = scandir($dir);
            foreach($child_dirs as $child_dir){
                if($child_dir != '.' && $child_dir != '..' && $child_dir != "extra" && !strstr($child_dir, '.')){
                    $files[] = $child_dir;
                }
            }
        }
        return $files;
    }
    /*
     * 验证器
     * */
    //验证器页面
    public function validata1(){
        $tbs = $this->tables();
        $this->assign('tables',$tbs);
        return view();
    }
    //选择验证信息
    public function validata2(){
        $name = input("validata_name");
        $this->assign('validata_name',$name);
        $table = input("table");
        if($table!=''){
            $cls = $this->columns($table);
            $this->assign('cls',$cls);
        }
        $mokuai = $this->mokuai();
        $this->assign('mokuai',$mokuai);
        return view();
    }
    //生成验证代码
    public function validata3(){
        $vals = input('param.');
        $cls = $this->columns($vals['table']);
        $data = $this->validata($cls,$vals);
        $fields = "";
        if(empty($data['ss'])){
            foreach($cls as $c){
                if($c['Key'] != 'PRI'){
                    $fields.= "'".$c['Field']."',";
                }
            }
            $fields = trim($fields,',');
        }else{
            foreach($data['ss'] as $c){
                $fields.= "'".$c."',";
            }
            $fields = trim($fields,',');
        }
        if($vals['is_code'] == 2){

        }else{
            if(empty($vals['validata_name'])){
                $this->assign('table',$vals['table']);
            }else{
                $this->assign('table',$vals['validata_name']);
            }
            if(input('mokuai')){
                $mokuai = input('mokuai');
            }else{
                $mokuai = 'index';
            }
            $this->assign('rs',$data['rs']);
            $this->assign('ms',$data['ms']);
            $this->assign('fields',$fields);
            $this->assign('mokuai',$mokuai);
            return view();
        }

    }
    private function _getf($c){
        if($c['Comment']!=''){
            return $c['Comment'];
        }else
            return $c['Field'];
    }
    private function _name($k, $name, $value){
        if(empty($name)){
            $data = $this->_getf($k).$value;
        }else{
            $data = $name .$value;
        }
        return $data;
    }
    //验证代码
    public function validata($cls,$vals){
        $ms = array();
        $rs = array();
        $ss = array();
        for($k=0;$k<count($cls);$k++){
            $c = $cls[$k]['Field'];
            if(isset($vals[$c])){
                if($vals[$c]=='on'){
                    $ss[] = $c;
                }
            }
            if(isset($vals[$c.'_'.'require'])){
                if($vals[$c.'_'.'require']=='on'){
                    $rs[$c][]='require';
                    $ms[$c.'.require'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'必填');
                }
            }
            if(isset($vals[$c.'_'.'number'])){
                if($vals[$c.'_'.'number']=='on'){
                    $rs[$c][]='number';
                    $ms[$c.'.number'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为数字');
                }
            }
            if(isset($vals[$c.'_'.'float'])){
                if($vals[$c.'_'.'float']=='on'){
                    $rs[$c][]='float';
                    $ms[$c.'.float'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为小数');
                }
            }
            if(isset($vals[$c.'_'.'boolean'])){
                if($vals[$c.'_'.'boolean']=='on'){
                    $rs[$c][]='boolean';
                    $ms[$c.'.boolean'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为布尔');
                }
            }
            if(isset($vals[$c.'_'.'email'])){
                if($vals[$c.'_'.'email']=='on'){
                    $rs[$c][]='email';
                    $ms[$c.'.email'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为邮箱格式');
                }
            }
            if(isset($vals[$c.'_'.'accepted'])){
                if($vals[$c.'_'.'accepted']=='on'){
                    $rs[$c][]='accepted';
                    $ms[$c.'.accepted'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为是和否');
                }
            }
            if(isset($vals[$c.'_'.'date'])){
                if($vals[$c.'_'.'date']=='on'){
                    $rs[$c][]='date';
                    $ms[$c.'.date'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为日期');
                }
            }
            if(isset($vals[$c.'_'.'alpha'])){
                if($vals[$c.'_'.'alpha']=='on'){
                    $rs[$c][]='alpha';
                    $ms[$c.'.alpha'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为字母');
                }
            }
            if(isset($vals[$c.'_'.'array'])){
                if($vals[$c.'_'.'array']=='on'){
                    $rs[$c][]='array';
                    $ms[$c.'.array'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为数组');
                }
            }
            if(isset($vals[$c.'_'.'alphaNum'])){
                if($vals[$c.'_'.'alphaNum']=='on'){
                    $rs[$c][]='alphaNum';
                    $ms[$c.'.alphaNum'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为字母数字');
                }
            }
            if(isset($vals[$c.'_'.'alphaDash'])){
                if($vals[$c.'_'.'alphaDash']=='on'){
                    $rs[$c][]='alphaDash';
                    $ms[$c.'.alphaDash'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为字母数字下划线等');

                }
            }
            if(isset($vals[$c.'_'.'activeUrl'])){
                if($vals[$c.'_'.'activeUrl']=='on'){
                    $rs[$c][]='activeUrl';
                    $ms[$c.'.activeUrl'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为域名/IP');

                }
            }
            if(isset($vals[$c.'_'.'url'])){
                if($vals[$c.'_'.'url']=='on'){
                    $rs[$c][]='url';
                    $ms[$c.'.url'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为URL');
                }
            }
            if(isset($vals[$c.'_'.'ip'])){
                if($vals[$c.'_'.'ip']=='on'){
                    $rs[$c][]='ip';
                    $ms[$c.'.ip'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为ip');

                }
            }
            if(isset($vals[$c.'_'.'phone'])){
                if($vals[$c.'_'.'phone']=='on'){
                    $rs[$c][]='regex:/^1[345789]\d{9}$/';
                    $ms[$c.'.phone'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为手机号格式');
                }
            }
            if(isset($vals[$c.'_'.'shen'])){
                if($vals[$c.'_'.'shen']=='on'){
                    $rs[$c][]='regex:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/i';
                    $ms[$c.'.shen'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'为身份证格式');
                }
            }
            if(isset($vals[$c.'_'.'regex'])){
                if($vals[$c.'_'.'regex']!=''){
                    $rs[$c][]='regex:'.$vals[$c.'_'.'regex'];
                    $ms[$c.'.regex'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'无法通过验证');
                }
            }
            if(isset($vals[$c.'_'.'confirm'])){
                if($vals[$c.'_'.'confirm']!=''){
                    $rs[$c][]='confirm:'.$vals[$c.'_'.'confirm'];
                    $ms[$c.'.confirm'] = $this->_name($cls[$k],'和'.$vals[$c.'_'.'confirm'],'不一致');
                }
            }
            if(isset($vals[$c.'_'.'max'])){
                if($vals[$c.'_'.'max']!=''){
                    $rs[$c][]='max:'.$vals[$c.'_'.'max'];
                    $ms[$c.'.max'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'最大值为'.$vals[$c.'_'.'max']);

                }
            }
            if(isset($vals[$c.'_'.'min'])){
                if($vals[$c.'_'.'min']!=''){
                    $rs[$c][]='min:'.$vals[$c.'_'.'min'];
                    $ms[$c.'.min'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'最小值为'.$vals[$c.'_'.'min']);
                }
            }
            if(isset($vals[$c.'_'.'before'])){
                if($vals[$c.'_'.'before']!=''){
                    $rs[$c][]='before:'.$vals[$c.'_'.'before'];
                    $ms[$c.'.before'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'必须在'.$vals[$c.'_'.'before'].'之前');
                }
            }
            if(isset($vals[$c.'_'.'after'])){
                if($vals[$c.'_'.'after']!=''){
                    $rs[$c][]='after:'.$vals[$c.'_'.'after'];
                    $ms[$c.'.after'] = $this->_name($cls[$k],$vals[$c.'_'.'name'],'必须在'.$vals[$c.'_'.'before'].'之后');
                }
            }
        }
        $data = [
            'rs' => $rs,
            'ms' => $ms,
            'ss' => $ss,
        ];
        return $data;
    }
}