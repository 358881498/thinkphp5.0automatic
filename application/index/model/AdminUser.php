<?php
/**
 *
 * 查询后台用户
 */

namespace app\business\model;
use think\Model;


class AdminUser extends Model
{
    // 成员 设置主表名
    protected $name = 'sys_user';

    //查询后台用户
    public function user_login($where)
    {
        $data = $this
            ->alias('u')
            ->where($where)
            ->find()->toArray();
        return $data;
    }
}