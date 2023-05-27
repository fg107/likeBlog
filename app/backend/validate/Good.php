<?php
namespace app\backend\validate;

use think\Validate;

class Good extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:255',
    ];
	protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过255个字符',
    ];

}