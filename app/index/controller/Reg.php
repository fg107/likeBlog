<?php
namespace app\index\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\facade\Session;
use think\captcha\facade\Captcha;
use app\index\validate\Authusers;

class Reg
{
    public function index()
    {
        return view();
    }

    public function verify()
    {
        if(request()->isPost())
        {
            $uname = input('post.uname');
            $res=Db::table('i_user')->where('uname',input('post.uname'))->find();/**查询数据**/
            if (isset($res) && !empty($res)) {
                echo 'true';
            }
        }
    }

    public function add()
    {
        if(request()->isPost())
        {
            $data=request()->param();
            $data['pwd'] = password_hash($data['pwd'],PASSWORD_BCRYPT);
            $data['create_time'] = time();
            $data['gender'] = intval($data['gender']);
            $data['tel'] = intval($data['tel']);
            $data['gender'] == 1 ? $data['avator']="/default/he.jpg" : $data['avator']="/default/she.jpg";
            $uid = Db::name('i_user')->insertGetId($data);
            if($uid){
                echo 'true';  
            }
            
            
             
        }
    }
}
    