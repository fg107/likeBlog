<?php
/**规则验证器**/
namespace app\backend\validate;

use think\Validate;

class Authrule extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:80',
        'title' =>  'require|max:20',
		
    ];
    
    protected $message  =   [
        'name.require' => '规则名称不能为空',
        'name.max'     => '规则名称最多不能超过80个字符',
        'title.require' => '规则描述不能为空',
        'title.max'     => '规则描述最多不能超过20个字符',
		
    ];
    
	/**使用场景**/
    protected $scene = [
        'addrule'  =>  ['name','title'], /**新增规则**/
		
		'updaterule'  =>  ['name','title'], /**编辑规则**/
    ];
}
?>