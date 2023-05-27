<?php 
namespace app\index\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\facade\Session;
use think\captcha\facade\Captcha;
use app\index\validate\Authusers;
class Login extends BaseController
{
	
    public function index()
    {
		header("Access-Control-Allow-Origin:*");
    	header("Access-Control-Allow-Headers: Authorization, Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, X-Requested-By, If-Modified-Since, X-File-Name, X-File-Type, Cache-Control, Origin");
		if (request()->isPost()){
			$pwd=input('post.pwd');
			$data=[
				'uname'=>input('post.uname'),
				'pwd'=>$pwd,
			];
			 
			$result=validate(Authusers::class)->scene('login')->check([
				'uname'  => input('post.uname'),
				'pwd' =>input('post.pwd'),
			]);
			 
			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				 
				$res=Db::table('i_user')->where('uname',input('post.uname'))->find();/**查询数据**/
				 
				
				if(password_verify($pwd,$res['pwd'])){
					Session::set('uid',$res['uid']);
					Session::set('uname',$res['uname']);
					echo json_encode(['uid'=>$res['uid'],'uname'=>$res['uname']]);
				 
					 
					 
				}else{
					echo json_encode(['status'=>0,'msg'=>'用户名或密码错误']);
					 
				}
			}
			 
		}else{
			
			return view('index');
		}
		
        
    }

	 
	
	/**验证码**/
	
	public function verify()
    {
		
        return Captcha::create('verify');    
    }
	
	
	/**验证码实时验证**/

	public function checkcapcha(){
	 
		if( !Captcha::check(input('post.captcha')) )
		{	 
			//验证不通过
			return false;
			exit;
		}else{
			return true;
			exit;
		}
	}
	
	
}
