<?php
namespace app\backend\validate;

use think\Validate;

class Articles extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:255',
    ];
	protected $message  =   [
        'title.require' => '名称必须',
        'title.max'     => '名称最多不能超过255个字符',
    ];

}