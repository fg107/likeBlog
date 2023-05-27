<?php
namespace app\index\controller;
use think\facade\Db;/**加载数据库**/
use lib\Rule;/**无限极分类扩展类库**/
use think\facade\View;
use app\BaseController;
use think\facade\Session;
class Detail extends BaseController
{
    public function index()
    {
        if (request()->isGet()) {
            //获取博客文章id
            $id = input('get.aid');
            $catid = input('get.cid');
            /*获取全部分类*/
            $rule = Db::table('cate')->field('catid,pid,catname')->select();
           
            $list = Rule::getParents($rule,$catid);
            
            $res = Db::table('article') 
            ->field('title,keyword,preface,content,description,create_time')
            ->where('id',$id)
            ->where('status',1)
            ->select()->toArray();
            foreach ($res as $k => $v) {
                
                $res[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
            //获取登陆人信息
            $info = Db::table('i_user')->where(['status'=>1,'uid'=>session::get('uid')])->field('uname,avator,gender')->find();
            
            //获取广告
            $ad = Db::table('ad')->where('status',1)->field('id,link,ad_code,description')->select()->toArray();
            foreach ($ad as $k => $v) {
                $ad[$k]['ad_code'] = json_decode($v['ad_code']);
            }


            //连表查询获得获取评论者个人信息与评论内容
            $rule = Db::table('comm')
            ->alias('c')
            ->where(['i.status'=>1,'c.status'=>1,'c.blog_id'=>$id])
            ->order('c.create_time','desc')
            ->field('c.id,c.con,c.create_time,c.pid,i.uname,i.gender,i.avator')
            ->leftJoin('i_user i','i.uid = c.uid')
            ->select()->toArray();
            
            foreach ($rule as $k => $v) {
                $rule[$k]['create_time'] =  self::GetDateuk($v['create_time']);
            }
         
            $comm = Rule::Rulelayer($rule,$pid=0);
            
             
            return view('index',['list'=>$list,'res'=>$res,'ad'=>$ad,'id'=>$id,'comm'=>$comm,'info'=>$info]);
        }
    }
    public function GetDateuk($mktime) 
    { 
        $oktime=time(); 
        if ($oktime-$mktime<60) 
        
        { 
        return "刚刚"; 
        } 
        
        if (($oktime-$mktime>=60) && ($oktime-$mktime<3600) ) 
        
        { 
        $a=trim(ceil(($oktime-$mktime)/60)); 
        return $a.'分钟前'; 
        } 
        if (($oktime-$mktime>=3600) && ($oktime-$mktime<86400) ) 
        
        { 
        $a=trim(ceil(($oktime-$mktime)/3600)); 
        return $a.'小时前'; 
        } 
        if (($oktime-$mktime>=86400) && ($oktime-$mktime<864000)) 
        
        { 
        $a=trim(ceil(($oktime-$mktime)/86400)); 
        return $a."天前"; 
        } 
        
        if ($oktime-$mktime>=864000 ) 
        { 
        return Date("Y-m-d",$mktime); 
        } 
     
    } 

    public function comm()
    {
        if(request()->isPost())
        {
             
            $data = $data=request()->param();
            $data['create_time'] = time();

            $data['uid']=session::get('uid');
            $data['blog_id']=intval($data['blog_id']);
            $data['pid']=intval($data['pid']);
            $in = Db::name('comm')->insertGetId($data);
           
            if($in){
                echo json_encode(["status"=>1,"com_id"=>$in,"pid"=>$data['pid']]);
            }
            
           
         
             
        }
    }

    public function del()
    {
        if(request()->isPost())
        {
             
            $com_id = input('post.com_id');
            $res = Db::name('comm')->delete($com_id);
           
            if($res){
                echo "true";
            } else{
                echo "false";
            }
        }
    }


    // public function hide()
    // {
    //     if(request()->isPost())
    //     {
    //         $data = [
    //             'status' => 0
    //         ];
    //         $com_id = input('post.com_id');
    //         $res = Db::name('comm')->where('id',$com_id)->update($data);
             
    //         if($res){
    //             echo "true";
    //         } else{
    //             echo "false";
    //         }
    //     }
    // }
}