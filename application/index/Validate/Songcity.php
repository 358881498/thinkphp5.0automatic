<?php
/**
 * 登录验证
 */
namespace app\index\validate;
use think\Validate;


class Songcity extends Validate
{
    protected $rule = [
        ['name', 'require', '歌城名字不能为空'],
        ['rooms', 'require', '包间数不能为空'],
        ['detail_location','require', '详细地址不能为空'],
        ['business_hours', 'require', '营业时间不能为空'],
        ['phone', 'require|regex:/^1[345789]\d{9}$/', '电话号码不能为空|请输入正确的电话号码'],
        ['city', 'require', '城市'],
    ];
}
