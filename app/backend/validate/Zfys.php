<?php
namespace app\backend\validate;

use think\Validate;

class Zfys extends Validate
{
    protected $rule = [
        'zfy'  =>  'require|max:120',
    ];
	protected $message  =   [
        'zfy.require' => '必须',
        'zfy.max'     => '最多不能超过120个字符',
    ];

}