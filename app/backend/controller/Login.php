<?php
namespace app\backend\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\facade\Session;
use think\captcha\facade\Captcha;
use app\backend\validate\Authusers;
class Login extends BaseController
{
    public function index()
    {
     
		if (request()->isPost()){
			$pwd=input('post.pwd');
			$data=[
				'uname'=>input('post.uname'),
				'pwd'=>$pwd,
			];
			try {
			$result=validate(Authusers::class)->scene('login')->check([
				'uname'  => input('post.uname'),
				'pwd' =>input('post.pwd'),
			]);
			
			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$res=Db::table('users')->where('uname',input('post.uname'))->find();/**查询数据**/
				
				if(password_verify($pwd,$res['pwd'])){
						Session::set('uid',$res['uid']);
						Session::set('uname',$res['uname']);
						echo 'true';
					}else{
						echo 'false';
					}
			}
			
			} catch (ValidateException $e) {
            // 验证失败 输出错误信息
				dump($e->getError());
			}
		}else{
			
			return view('index');
		}
		
       // return view('index', ['name' => 'thinkphp']);
    }

    
	
	/**验证码**/
	
	public function verify()
    {
		
        return Captcha::create('verify');    
    }
	
	
	/**验证码实时验证**/
	public function checkcapcha(){
	$captcha = new Captcha();
	if(!captcha_check(input('post.captcha')))
	{
	return false;
	exit;
	}else{
	return true;
	exit;
	}
	}
	
	
}
