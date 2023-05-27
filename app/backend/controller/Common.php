<?php
namespace app\backend\controller;
 use app\BaseController;
 use lib\Auth;/**权限认证类**/
 use think\facade\Session;
class Common extends BaseController
{
	public function  initialize(){
		
			$sess_auth=session('uid');    //判断是否登录 没有登录跳转到登录页
			$uname=session('uname'); 
			
			//print_r(Session::all());
			
			
			if(!$sess_auth){
				echo jumpTo('/login/index');
				exit;
			}else{
				//获取auth实例
				// $auth = Auth::instance();
				$auth=new Auth();   //实例化权限认证 $auth->checkI('规则','登录的用户id');
				if(!$auth->check(request()->controller().'/'.request()->action(), $sess_auth)){
					//echo $sess_auth;
					echo historyTo('没有操作该栏目的权限，请联系管理员!');
					exit;
				}
			}
			
			
			
			
			
			
			//echo app('http')->getName(); 获取应用名称
			
			
			//echo  $path = $this->request->controller();获取控制器
			//echo  $action = $this->request->action();  获取操作方法
			//echo request()->controller();
	}
}