<?php
namespace app\index\controller;
use app\index\Build;
use think\Loader;
class Index extends Base
{
    //生成文件
    //        $data['demo']     = [
//            '__file__'   => ['common.php'],
//            '__dir__'    => ['behavior', 'controller', 'model', 'view'],
//            'controller' => ['Index', 'Test', 'UserType'],
//            'model'      => ['User', 'UserType'],
//            'view'       => ['index/index'],
//        ];
//        $build = new Build;
//        $build->run($data);
    //首页
    public function index()
    {
        return view();
    }
    //公共文件页面
    public function common(){
        return view();
    }
    //验证器页面
    public function page_validata_step1(){
        $tbs = $this->tables();
        $this->assign('tables',$tbs);
        return view();
    }
    //选择验证信息
    public function page_validata_step2(){
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
    public function page_validata_step3(){
        $vals=input('post.');
        $cls = $this->columns($vals['table']);
        $rs = array();
        $ms = array();
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
        $fields = "";
        if(empty($ss)){
            foreach($cls as $c){
                if($c['Key'] != 'PRI'){
                    $fields.= "'".$c['Field']."',";
                }
            }
            $fields = trim($fields,',');
        }else{
            foreach($ss as $c){
                $fields.= "'".$c."',";
            }
            $fields = trim($fields,',');
        }
        if($vals['table']){

        }
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
        $this->assign('rs',$rs);
        $this->assign('ms',$ms);
        $this->assign('fields',$fields);
        $this->assign('mokuai',$mokuai);
        return view();
    }
    //配置文件
    public function extra(){
        return view();
    }
    //控制器
    public function page_controller_step1(){
        $mokuai = $this->mokuai();
        $this->assign('mokuai',$mokuai);
        $tbs = $this->tables();
        $this->assign('tables',$tbs);
        return view();
    }
    //获取模型方法
    public function getmodelfunction(){
        $data = input('param.');
        $obj = $this->getActions($data['model'],$data['mokuai']);
        return json($obj);
    }
    //模型方法
    public function getActions($className) {
        $methods = get_class_methods(model($className));
        $baseMethods = get_class_methods(model('base'));
        $res = array_diff($methods, $baseMethods);
        return $res;
    }
    public function controller_step2(){
        $mokuai = input('mokuai');
        if(empty($mokuai)){
            return false;
        }
        $dir = dirname(dirname(dirname(__FILE__))).'\\'.$mokuai."\\validate";
        $dir1 = dirname(dirname(dirname(__FILE__))).'\\'.$mokuai."\\model";
        $files = array();
        if(is_dir($dir)){
            $child_dirs = scandir($dir);
            foreach($child_dirs as $child_dir){
                if(strstr($child_dir, '.php')){
                    $files['validate'][] = basename($child_dir,".php");
                }
            }
        }
        if(is_dir($dir1)){
            $child_dirs = scandir($dir1);
            foreach($child_dirs as $child_dir){
                if(strstr($child_dir, '.php')){
                    $files['model'][] = basename($child_dir,".php");
                }
            }
        }
        return json($files);
    }
    //生成控制器代码
    public function page_controller_step2($table){
        $data = input('param.');
//        unset($data['controller_name']);
        print_r($data);
        $this->assign('data',$data);
        $key ='';
        $wheresql = '';
        if($table!=''){
            $cls = $this->columns($table);//print_r($cls);exit;
            foreach ($cls as $c){
                if($c['Key']=='PRI')
                    $key = $c['Field'];
                if(strstr($c['Type'],'varchar')!=''){
                    $wheresql.=" and ".$c['Field']." like binary '%\$keyword%' ";
                }
            }

        }
        $this->assign('wheresql',$wheresql);
        $this->assign('tb',$table);
        $this->assign('key',$key);
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
     * 生成模型代码
     * */
    //模型页面
    public function page_model_step1(){
        $this->assign('type',input('id'));
        $this->assign('mokuai',$this->mokuai());
        //代码
        $tables = $this->tables();
        $this->assign('tables',$tables);
        return view();
    }
    //数据表名转换成符合模型命名规格的字符串
    public function parseName($name, $type = 0, $ucfirst = true){
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);
            return $ucfirst ? ucfirst($name) : lcfirst($name);
        }
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
    //生成模型代码
    public function page_model_step2(){
        $data = input('param.');
        if(empty($data['table'])){
            $this->error('缺少表');
            return;
        }
        $model = empty($data['model']) ? 'Model': $data['model'];
        $mokuai = empty($data['mokuai']) ? 'index': $data['mokuai'];
        $cls = $this->columns($data['table']);
        $key = $autotime = $issoftdelete = null;
        foreach ($cls as $c){
            if($c['Key']=='PRI'){
                $key = $c['Field'];
            }else if($c['Field'] == 'create_time'){
                $autotime = 1;
                $type = $c['Type'];
                $this->assign('type',$type);
            }else if($c['Field'] == 'update_time'){
                $autotime= 1;
                $type = $c['Type'];
                $this->assign('type',$type);
            }else if($c['Comment'] == '软删除'){
                $issoftdelete = 1;
                $this->assign('delfield',$c['Field']);
            }
        }
        $array[] = $data['table'];
        $data['table'] = $this->parseName(array_pop($array), 1);
        $this->assign('table',$data['table']);
        $this->assign('modelLayer',$data['modelLayer']);
        $this->assign('model',$model);
        $this->assign('mokuai',$mokuai);
        $this->assign('autotime',$autotime);
        $this->assign('issoftdelete',$issoftdelete);
        $this->assign('pk',$key);
        return view();
    }
    //生成模型文件
    public function model3(){
        $data = input('param.');
        $model = empty($data['model']) ? 'Model': $data['model'];
        $mokuai = empty($data['mokuai']) ? 'index': $data['mokuai'];
        echo "<pre>";
        print_r($data);
    }
    public function page_form_step1(){
        $tbs = $this->tables();
        $this->assign('tables',$tbs);
        return view();
    }
    
    public function page_form_step2(){
        $d = input('get.');
        $table = $d['table'];
        $style = $d['style'];//h水平 b基本 i内联
        $key = '';

        if($table!=''){
        
            $cls = $this->columns($table);//print_r($cls);
            foreach ($cls as $c){
                if($c['Key']=='PRI'){
                    $key = $c['Field'];
                    break;
                }
            }
            $this->assign('cls',$cls);
            $this->assign('key',$key);
            $this->assign('table',$table);
            $this->assign('style',$style);
                        
        }else{
            $this->error('请选择要操作的数据表！');
        }
        
        return view();
    }
    
    public function page_table_step1(){
        $tbs = $this->tables();
        $this->assign('tables',$tbs);
        return view();
    }
    
    public function page_table_step2(){
        $d = input('post.');
        $table = $d['table'];//dump(input('post.style'));exit;
        $styles ='';
        if(isset($d['style']))
            $styles = implode(' ',$d['style']);
        $key="";
        
        if($table!=''){
            $cls = $this->columns($table);//print_r($cls);
            foreach ($cls as $c){
                if($c['Key']=='PRI'){
                    $key = $c['Field'];
                    break;
                }
            }
            $this->assign('cls',$cls);
            $this->assign('k',$key);
            $this->assign('table',$table);
            $this->assign('styles',$styles);
        }else{
            $this->error('请选择要操作的数据表！');
        }
        
        return view();
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

    //公共控制器
    public function basecontroller(){
        if(input('mokuai')){
            $mokuai = input('mokuai');
        }else{
            $mokuai = 'index';
        }
        $this->assign('mokuai',$mokuai);
        return view();
    }
    //登录控制器
    public function logincontroller(){
        if(input('mokuai')){
            $mokuai = input('mokuai');
        }else{
            $mokuai = 'index';
        }
        $this->assign('mokuai',$mokuai);
        return view();
    }
    //空控制器
    public function errorontroller(){
        if(input('mokuai')){
            $mokuai = input('mokuai');
        }else{
            $mokuai = 'index';
        }
        $this->assign('mokuai',$mokuai);
        return view();
    }
}
