<?php
namespace app\index\controller;
use think\facade\Db;/**加载数据库**/
use lib\Rule;/**无限极分类扩展类库**/
use think\facade\View;
use app\BaseController;
use think\facade\Session;
class Index extends BaseController
{
    public function index()
    {
         
        $res=Db::table('cate')->field('catid,pid,catname,thumb')->select()->toArray();
          
         
        $list = Rule::Rulelayer($res,$pid=0);
    
     
        
        foreach ($list as $k => $v) {
            $list[$k]['thumb'] = json_decode($v['thumb']);
        }
         
        return  view('index',['list'=>$list]);

    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function logout(){
        
		Session::clear(); 
		return redirect('/index');
	}
}
