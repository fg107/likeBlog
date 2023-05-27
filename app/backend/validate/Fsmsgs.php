<?php
namespace app\backend\validate;

use think\Validate;

class Fsmsgs extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:120',
    ];
	protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过120个字符',
    ];

}