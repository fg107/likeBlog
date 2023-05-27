<?php
namespace app\backend\validate;

use think\Validate;

class Cates extends Validate
{
    protected $rule = [
        'catname'  =>  'require|max:120',
    ];
	protected $message  =   [
        'catname.require' => '名称必须',
        'catname.max'     => '名称最多不能超过120个字符',
    ];

}