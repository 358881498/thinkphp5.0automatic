<?php
/**
 * 登录验证
 */
namespace app\business\validate;
use think\Validate;


class Addlucky extends Validate
{
    protected $rule = [
        ['lucky_name', 'require', '抽奖名不能为空'],
        ['lucky_num', 'require|number', '奖品数量不能为空|数量必须为数字'],
        ['lucky_time','require', '开奖时间不能为空'],
        ['lucky_integral', 'require|number', '抽奖消耗积分不能为空|积分必须为数字'],
        ['lucky_discipline', 'require', '抽奖规则不能为空'],
    ];
}
