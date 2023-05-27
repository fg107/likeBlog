<?php
namespace app\backend\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use lib\Rule;/**无限极分类扩展类库**/
use think\facade\View;
use think\facade\Session;
class Index extends Common
{
	public function menu(){
		
	}
    public function index()
    {
		  
		 
		$uid=session('uid');
		$uname=session('uname');
		
		/**权限菜单控制**/
		$sql="select u.uid,u.uname,u.pwd,u.create_time,u.status as status,u.login_ip,gr.uid as gruid,gr.group_id,g.id as gid,g.title,g.status as gstatus,g.rules  from users as u left join auth_group_access as gr on gr.uid = u.uid left join auth_group as g on g.id = gr.group_id where u.uid='".$uid."' group by u.uid";
		$res = Db::query($sql);
		$rid=array_column($res, 'rules');  
		$rids=implode(" ",$rid);
		$rules=explode(",",$rids);
		
		/**角色菜单**/
		$rer =Db::table('auth_rule')->order('id','asc')->where('id','in',$rules)->where(['is_menu'=>1])->select();
		 
		$rlist=Rule::Rulelayers($rer); /**分类递归**/
	 
		//print_r($rlist);
		$data=[
			'uid'=>session('uid'),
			'uname'=>session('uname'),
			'rlist'=>$rlist
		];
		
		
		return view('index',$data);
       
    }

    
	
	public function welcome(){
		return view('welcome');
	}
	
	public function logout(){
		Session::clear(); 
		 return redirect('/login/index');
	}
}
