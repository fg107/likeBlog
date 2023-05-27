<?php
namespace app\backend\validate;

use think\Validate;

class Settings extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:120',
        'mobile'  =>  'require|max:11',
    ];
	protected $message  =   [
        'title.require' => '名称必须',
        'title.max'     => '名称最多不能超过120个字符',
		'mobile.require' => '手机必须',
        'mobile.max'     => '手机最多不能超过11个字符',
        
    ];

}