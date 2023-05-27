<?php
/**规则验证器**/
namespace app\backend\validate;

use think\Validate;

class Authusers extends Validate
{
    protected $rule = [
        'uname'  =>  'require|max:120',
        'pwd' =>  'require|max:225',
		'pwd_confirm'=>'require|confirm',/**验证一致性**/  
		
    ];
    
    protected $message  =   [
        'uname.require' => '用户名不能为空',
        'uname.max'     => '用户名最多不能超过120个字符',
        'pwd.require' => '密码不能为空',
        'pwd.max'     => '密码最多不能超过225个字符',
		'pwd_confirm.confirm'     => '两次输入的密码不一致',
		
    ];
    
	/**使用场景**/
    protected $scene = [
        'add'  =>  ['uname','pwd'], /**新增**/
		'update'  =>  ['uname'],/**编辑**/
		'resetpwd'  =>  ['pwd','pwd_confirm'],/**重置密码**/
		'login'  =>  ['uname','pwd'], /**登陆**/
    ];
}
?>