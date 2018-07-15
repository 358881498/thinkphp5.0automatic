<?php
namespace app\index\model;

use think\Model;

class Order extends Model
{
    // 成员 设置主表名
    protected $name = 'demand_order';
    //充值记录
    public function topuprecord($where = null, $where1 = null)
    {
        $data = db('mem_transaction_record')
            ->alias('tr')
            ->join('member m','tr.user_id = m.id')
            ->join('user_profile up','tr.user_id = up.user_id')
            ->where('up.nickname','like',"%$where%")
            ->where($where1)
            ->whereOr('m.phone',$where)
            ->field('tr.*,m.user_name uname,m.phone,up.nickname')
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //订单列表
    public function orderlist($where = null)
    {
        $data = $this
            ->where($where)
            ->paginate(config("admin.listRows"),false,['query'=>request()->param()]);
        return $data;
    }
    //非首单扣除金额
    public function notopmoney()
    {
        $id = db('sys_dict')
            ->where('name','notopmoney')
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
    //订单详情
    public function orderdetail($where)
    {
        $data = $this
            ->where($where)
            ->find();
        //参与歌手信息
        $data['detail'] = db('order_detail')
            ->alias('o')
            ->join('user_profile up','o.singer_id = up.user_id')
            ->where('o.order_id',$data['id'])
            ->select();
        //订单评价
        $data['evaluation'] = db('order_evaluation')
            ->alias('o')
            ->where('o.order_id',$data['id'])
            ->select();
        //投诉
        $data['complaints'] = db('order_complaint')
            ->alias('o')
            ->where('o.order_id',$data['id'])
            ->select();
        return $data;
    }
}