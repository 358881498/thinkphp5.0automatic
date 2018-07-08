<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Url;

class Base extends Controller
{
    //查询数据库所有表信息
    protected function tables()
    {
        $tables = Db::query('show table status');
        return $tables;
    }
    //查询表中所有字段信息
    protected function columns($table){
        $columns = Db::query("SHOW FULL COLUMNS FROM ".$table);
        return $columns;
    }
    
    
}
