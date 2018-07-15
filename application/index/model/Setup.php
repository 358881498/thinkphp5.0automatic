<?php
/*
 *
 * 设置模型
 * */
namespace app\index\model;

use think\Model;

class Setup extends Model
{
    // 成员 设置主表名
    protected $name = 'app_banner';
    //banner列表
    public function bannerlist()
    {
        $data = $this
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //banner信息，编辑添加页面
    public function banner($where = null){
        $data = $this
            ->where($where)
            ->find();
        return $data;
    }
    //banner编辑、添加提交信息
    public function savebanner($postdata)
    {
        if(empty($postdata['id'])){
            $postdata['crt_by'] = session("admin_user.user_id");
            $postdata['crt_time'] = date('Y-m-d h:i:s',time());
            //添加
            $data = $this
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        $postdata['upd_by'] = session("admin_user.user_id");
        $postdata['upd_time'] = date('Y-m-d h:i:s',time());
        //修改
        $data = $this
            ->update($postdata);
        return $data;
    }
    //歌城列表
    public function songcitylist($where = null)
    {
        if($where){
            $data = db('song_city')
                ->where($where)
                ->find();
            return $data;
        }
        $data = db('song_city')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //添加、修改歌城数据
    public function savesongcity($postdata)
    {
        if(empty($postdata['id'])){
            //添加
            $data = db('song_city')
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        //修改
        $data = db('song_city')
            ->update($postdata);
        return $data;
    }
    //积分配置
    public function integralset($postdata = null)
    {
        if(empty($postdata)){
            $where['sd.name'] = 'point_conf';
            $data = db('sys_dict')
                ->alias('sd')
                ->join('sys_dict sd2','sd2.up_id = sd.id')
                ->where($where)
                ->field('sd2.*,sd.name as name1,sd.value as value1')
                ->find();
            return $data;
        }else{
            //修改
            $data = db('sys_dict')
                ->update($postdata);
            return $data;
        }
    }
    //抽奖列表
    public function luckylist(){
        $data = db('mem_lucky')
            ->order('lucky_time','desc')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //find抽奖
    public function findlucky($where){
        $data = db('mem_lucky')
            ->where($where)
            ->find();
        return $data;
    }
    //add抽奖
    public function addlucky($postdata){
        if(empty($postdata['id'])){
            $postdata['crt_time'] = date('Y-m-d h:i:s',time());
            //添加
            $data = db('mem_lucky')
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        //修改
        $data = db('mem_lucky')
            ->update($postdata);
        return $data;
    }
    //查询抽奖人
    public function lucky_people($where){
        $data = db('mem_lucky_people')
            ->alias('lp')
            ->join('member m','lp.user_id = m.id')
            ->join('user_profile up','lp.user_id = up.user_id')
            ->where($where)
            ->field('up.nickname,lp.*,m.phone')
            ->order('lp.crt_time','desc')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //用户意见反馈
    public function feedback(){
        $data = db('app_feedback')
            ->alias('f')
            ->join('member m','m.id = f.user_id')
            ->field('f.*,m.user_name')
            ->order('f.crt_time','desc')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //区域加盟列表
    public function arealist(){
        $data = db('mem_regional_joining')
            ->alias('r')
            ->join('member m','m.id = r.user_id')
            ->field('r.*,m.user_name login')
            ->order('r.crt_time','desc')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //区域加盟详情
    public function areadetails($where = null){
        $data = db('mem_regional_joining')
            ->alias('r')
            ->join('member m','m.id = r.user_id')
            ->where($where)
            ->field('r.*,m.user_name login')
            ->select();
        return $data;
    }
    //邀请设置
    public function invitation(){
        $id = db('sys_dict')
            ->where('name','invitation')
            ->field('id')
            ->find();
        if($id){
            $data = db('sys_dict')
                ->where('up_id',$id['id'])
                ->select();
            return $data;
        }
        return false;
    }
    //客服电话
    public function servicephone(){
        $id = db('sys_dict')
            ->where('name','service_phone')
            ->field('id')
            ->find();
        if($id){
            $data = db('sys_dict')
                ->where('up_id',$id['id'])
                ->select();
            return $data;
        }
        return false;
    }
    //问题分类
    public function problemstype(){
        $data = db('mem_problem_type')
            ->select();
        foreach ($data as $k=>$v){
            $where['problems_type_id'] = $v['type_id'];
            $data[$k]['data'] = $this->problems($where);
        }
        return $data;
    }
    //修改、添加问题
    public function saveproblems($postdata){
        if(empty($postdata['problems_id'])){
            //添加
            $data = db('mem_problems')
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        //修改
        $data = db('mem_problems')
            ->update($postdata);
        return $data;
    }
    //修改、添加问题分类
    public function saveproblemstype($postdata){
        if(empty($postdata['type_id'])){
            //添加
            $data = db('mem_problem_type')
                ->strict(true)
                ->insertGetId($postdata);
            return $data;
        }
        //修改
        $data = db('mem_problem_type')
            ->update($postdata);
        return $data;
    }
    //问题列表
    public function problems($where){
        $data = db('mem_problems')
            ->where($where)
            ->select();
        return $data;
    }
}