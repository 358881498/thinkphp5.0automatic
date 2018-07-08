<?php
/*
 * 系统设置模型
 * */
namespace app\business\model;
use think\Model;
use think\Db;
use think\Paginator;

class System extends Model
{
    // 成员 设置主表名
    protected $name = 'sys_menu';
    protected $pk = 'menu_id';
    //菜单栏
    public function menu($where){
        $data = $this
            ->alias('m')
            ->where('rm.role_id',$where)
            ->join('sys_role_menu rm','m.menu_id = rm.menu_id')
            ->order('order_num','asc')
            ->select()
            ->toArray();
        return $data;
    }
    //系统所有的菜单
    public function allmenu(){
        $data = $this
            ->alias('m')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //系统所有的一级单
    public function onemenu(){
        $data = $this
            ->where('parent_id',0)
            ->order('order_num','asc')
            ->select();
        return $data;
    }
    //修改、添加菜单
    public function savemenu($postdata){
        if($postdata['types'] == 1){
            unset($postdata['types']);
            //添加
            $data = db('sys_menu')
                ->strict(true)
                ->insert($postdata);
            if($data){
                $data = db('sys_menu')->getLastInsID();
            }
            return $data;
        }
        //修改
        unset($postdata['types']);
        $data = db('sys_menu')
            ->update($postdata);
        return $data;
    }
    //查询一个菜单
    public function findmenuone($id){
        $data = $this
            ->where('menu_id',$id)
            ->find();
        return $data;
    }
    //查询所有系统用户
    public function adminuser(){
        $data = db('sys_user')
            ->alias('u')
            ->join('sys_role sr','u.role_id = sr.role_id')
            ->field('u.*,sr.role_name')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //查询单个系统用户
    public function finduser($where){
        $data = db('sys_user')
            ->alias('u')
            ->where($where)
            ->join('sys_role sr','u.role_id = sr.role_id')
            ->find();
        return $data;
    }
    //修改、添加用户
    public function saveuser($postdata){
        if($postdata['type'] == 1){
            unset($postdata['type']);
            //添加
            $data = db('sys_user')
                ->strict(true)
                ->insert($postdata);
            if($data){
                $data = Db::name('user')->getLastInsID();
            }
            return $data;
        }
        unset($postdata['type']);
        $data = db('sys_user')
            ->update($postdata);
        return $data;
    }
    //查询所有系统角色
    public function findrole(){
        $data = Db::table('sys_role')
            ->select();
        return $data;
    }
    //查询单个系统角色
    public function findroleone($where)
    {
        $data = Db::table('sys_role')
            ->alias('u')
            ->where($where)
            ->join('sys_role sr','u.role_id = sr.role_id')
            ->find();
        return $data;
    }
    //修改、添加角色
    public function saverole($postdata){
        if($postdata['type'] == 1){
            unset($postdata['type']);
            //添加
            $data = db('sys_role')
                ->strict(true)
                ->insert($postdata);
            if($data){
                $data = db('sys_role')->getLastInsID();
            }
            return $data;
        }
        //修改
        unset($postdata['type']);
        $data = db('sys_role')
            ->update($postdata);
        return $data;
    }
    //修改权限
    public function permissions($data){
        //第一步，删除权限
        $this->delpermissions($data['id']);
        //第2步，添加对应的权限
        return $this->addpermissions($data);
    }
    //查询角色是否有用户在用，为后续删除做准备
    public function role_is_del($id){
        $count = db('sys_user')
            ->where('role_id',$id)
            ->count();
        return $count;
    }
    //删除权限
    public function delpermissions($id){
        db('sys_role_menu')
            ->where('role_id',$id)
            ->delete();
        return;
    }
    //add权限
    public function addpermissions($data){
        $list = array();
        if(empty($data['menuid'])){
            return false;
        }
        foreach ($data['menuid'] as $k){
            $list[] = ['role_id'=>$data['id'],'menu_id'=>$k];
        }
        return db('sys_role_menu')->insertAll($list);;
    }
    //查询菜单是否有角色在用，为后续删除做准备
    public function menu_is_del($id){
        $count = db('sys_role_menu')
            ->where('menu_id',$id)
            ->count();
        return $count;
    }
    //查询所有字典
    public function alldictionary(){
        $data = db('sys_dict')
            ->order('order_num')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //查询字典
    public function dictionary(){
        $data = db('sys_dict')
            ->where('up_id',0)
            ->select();
        return $data;
    }
    //查询单个字典
    public function finddictionaryone($where)
    {
        $data = Db::table('sys_dict')
            ->where($where)
            ->find();
        return $data;
    }
    //添加、修改字典
    public function savedictionary($postdata){
        if($postdata['types'] == 1){
            unset($postdata['types']);
            //添加
            $data = db('sys_dict')
                ->strict(true)
                ->insert($postdata);
            if($data){
                $data = db('sys_dict')->getLastInsID();
            }
            return $data;
        }
        //修改
        unset($postdata['types']);
        $data = db('sys_dict')
            ->update($postdata);
        return $data;
    }
}