<?php
/**规则验证器**/
namespace app\backend\validate;

use think\Validate;

class Authgroup extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:100',
		
    ];
    
    protected $message  =   [
        'title.require' => '用户组名称不能为空',
        'title.max'     => '用户组名称最多不能超过100个字符',
		
    ];
    
	/**使用场景**/
    protected $scene = [
        'addgroup'  =>  ['title'], /**新增用户组**/
		'updategroup'  =>  ['title'], /**新增用户组**/
    ];
}
?>