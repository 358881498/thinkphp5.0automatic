<?php
/*
 *
 * 用户模型
 * */
namespace app\business\model;

use think\Model;
use think\Db;
class User extends Model
{
    // 成员 设置主表名
    protected $name = 'member';
    //用户列表
    public function memberlist($where = null){
        $type = [
            'up.singer_type' => 0,
        ];
        $data = $this
            ->alias('m')
            ->join('user_profile up','m.id = up.user_id')
            ->join('mem_certification_information ci','m.id = ci.user_id','left')
            ->join('mem_account a','m.id = a.user_id','left')
            ->join('mem_integral i','m.id = i.user_id','left')
            ->where($type)
            ->where('realname|nickname','like','%'.$where.'%')
            ->whereOr('m.phone',$where)
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //审核列表用户
    public function certificationlist($where = null, $search = null){
        $data = $this
            ->alias('m')
            ->join('user_profile up','m.id = up.user_id')
            ->join('mem_certification_information ci','m.id = ci.user_id')
            ->join('mem_personal_authentication pa','m.id = pa.user_id')
            ->where($where)
            ->where('up.realname|up.nickname','like','%'.$search.'%')
            ->whereOr('phone',$search)
            ->field('up.*,pa.*,ci.real_authentication')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //审核资料
    public function certificationdata($where){
        $data = $this
            ->alias('m')
            ->join('user_profile up','m.id = up.user_id')
            ->join('mem_certification_information ci','m.id = ci.user_id')
            ->join('mem_personal_authentication pa','m.id = pa.user_id','left')
            ->where($where)
            ->field('up.nickname,pa.*,phone,ci.real_authentication')
            ->find();
        return $data;
    }
    //歌手列表
    public function singerlist($where, $search = null){
        $data = $this
            ->alias('m')
            ->join('user_profile up','m.id = up.user_id')
            ->join('singer_category sc','up.singer_type = sc.id')
            ->join('singer_level sl','up.grade = sl.id')
            ->join('mem_account a','a.user_id = m.id')
            ->join('mem_integral i','i.user_id = m.id')
            ->join('mem_personal_authentication pa','pa.user_id = m.id')
            ->join('hot_recommended hr','hr.user_id = m.id','left')
            ->where('m.singer',1)
            ->where($where)
            ->where('up.realname|up.nickname','like','%'.$search.'%')
            ->whereOr('m.phone',$search)
            ->field('m.*,up.*,sc.name scname,sl.name slname,i.integral,a.balance,pa.audit_time,hr.valid')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //推荐
    public function recommended($id){
        $data = db('hot_recommended')
            ->strict(true)
            ->insertGetId($id);
        return $data;
    }
    //歌手信息
    public function singer($where){
        if($where){
            $data = $this
                ->alias('m')
                ->join('user_profile up','m.id = up.user_id')
                ->join('singer_category sc','up.singer_type = sc.id')
                ->join('singer_level sl','up.grade = sl.id')
                ->where('m.singer',1)
                ->where($where)
                ->find();
        }
        return $data;
    }
    //审核列表用户
    public function singerauditlist($where = null, $search = null){
        $data = $this
            ->alias('m')
            ->join('user_profile up','m.id = up.user_id')
            ->join('mem_singers_join sj','m.id = sj.user_id')
            ->where($where)
            ->where('up.realname|up.nickname','like','%'.$search.'%')
            ->whereOr('sj.phone',$search)
            ->field('up.realname,sj.*')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //审核资料
    public function singerauditdata($where){
        $data = db('mem_singers_join')
            ->alias('sj')
            ->join('user_profile up','sj.user_id = up.user_id')
            ->field('up.realname,sj.*')
            ->where($where)
            ->find();
        return $data;
    }
    //歌手类型列表
    public function singer_type(){
        $data = db('singer_category')
            ->select();
        return $data;
    }
    //歌手等级列表
    public function singer_level($where){
        $data = db('singer_level')
            ->where($where)
            ->select();
        return $data;
    }
    //编辑、添加歌手类型
    public function savesingertype($postdata){
        if($postdata['types'] == 1){
            unset($postdata['types']);
            //添加
            $data = db('singer_category')
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        //修改
        unset($postdata['types']);
        $data = db('singer_category')
            ->update($postdata);
        return $data;
    }
    //查询歌手类型是否有歌手在用，为后续删除做准备
    public function singertype_is_del($id){
        $count = db('user_profile')
            ->where('singer_type',$id)
            ->count();
        return $count;
    }
    //删除类型对应的等级
    public function delsingerlevel($id){
        $data = db('singer_level')
            ->where('category',$id)
            ->delete();
        return $data;
    }
    //编辑、添加歌手类型
    public function savesingerlevel($postdata){
        if($postdata['types'] == 1){
            unset($postdata['types']);
            //添加
            $data = db('singer_level')
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        //修改
        unset($postdata['types']);
        $data = db('singer_level')
            ->update($postdata);
        return $data;
    }
    //查询歌手类型是否有歌手在用，为后续删除做准备
    public function singerlevel_is_del($id){
        $count = db('user_profile')
            ->where('grade',$id)
            ->count();
        return $count;
    }
    //曲风、风格列表
    public function stylelist($id){
        $data = db('sys_label')
            ->where('type',$id)
            ->select();
        return $data;
    }
    //编辑、添加曲风、风格页面
    public function findstyle($where){
        $data = db('sys_label')
            ->where($where)
            ->find();
        return $data;
    }
    //编辑、添加曲风、风格数据
    public function savestyle($postdata){
        if($postdata['types'] == 1){
            unset($postdata['types']);
            //添加
            $data = db('sys_label')
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        //修改
        unset($postdata['types']);
        $data = db('sys_label')
            ->update($postdata);
        return $data;
    }
    //查询歌手类型是否有歌手在用，为后续删除做准备
    public function style_is_del($id){
        $count = db('mem_label')
            ->where('label_id',$id)
            ->count();
        return $count;
    }
}