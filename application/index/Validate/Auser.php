<?php
/**
 * 登录验证
 */
namespace app\business\validate;
use think\Validate;

class Auser extends Validate
{
    protected $rule = [
        ['user_name', 'require|min:6|alphaNum', '登录名不能为空|登录名不能少于6位数|登录名为字母数字'],
        ['password', 'require|min:6', '密码不能为空|密码不能少于6位数'],
        ['repassword','require|min:6|confirm:password', '确认密码不能为空|确认密码不能少于6位数|密码不一致'],
        ['nickname', 'require', '用户名不能为空'],
        ['phone', 'require|regex:/^1[345789]\d{9}$/', '电话号码不能为空|请输入正确的电话号码'],
        ['email', 'email', '邮箱地址为EMAIL'],

    ];
    protected $scene = [
        'add' => ['user_name', 'password','repassword','nickname','phone','email'],
        'save' => ['user_name', 'nickname','phone','email'],
    ];
}