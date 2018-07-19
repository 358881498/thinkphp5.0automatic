<?php
namespace app\index\model;

use think\Model;
//数据层模型 -- 绑定关联模型 -- 编写特殊需求数据
class sys_user extends Model
{
    protected $table = 'sys_user';
    protected $pk = 'id';

    //一对一关联模型
    public function hasOne()
    {
        return $this->hasOne('');
    }
    //一对多关联模型
    public function hasMany()
    {
        return $this->hasMany('');
    }
    //远程一对多关联模型
    public function topics()
    {
        return $this->hasManyThrough('','');
    }
    //一对一、一对多的相对关联模型
    public function belongsTo()
    {
        return $this->belongsTo('');
    }
    //多对多关联模型
    public function belongsToMany()
    {
        return $this->belongsToMany('');
    }
}
        