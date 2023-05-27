<?php
namespace app\index\controller;
use think\facade\Db;/**加载数据库**/
use lib\Rule;/**无限极分类扩展类库**/
use think\facade\View;
use app\BaseController;
use think\facade\Session;
class Lists extends BaseController
{
    public function index(){
        if (request()->isGet()) {
            $catid = input('get.id');
            $catname = input('get.name');
            /*获取全部分类*/
            $rule = Db::table('cate')->field('catid,pid,catname')->select();
           
            $list = Rule::getParents($rule,$catid);
            //连表查询，
            $res = Db::table('article')
            ->alias('a')
            ->where('a.catid',$catid)
            ->order('a.create_time','desc')
            ->field('a.id,a.title,a.thumb,a.create_time,a.preface,a.catid,c.catname')
            ->leftJoin('cate c','a.catid = c.catid')
            ->select()->toArray();
           
            
            foreach ($res as $k => $v) {
                $res[$k]['thumb'] = json_decode($v['thumb']);
                $res[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
             
            //获取广告
            $ad = Db::table('ad')->where('status',1)->field('id,link,ad_code,description')->select()->toArray();
            foreach ($ad as $k => $v) {
                $ad[$k]['ad_code'] = json_decode($v['ad_code']);
            }
            
            return view('index',['list'=>$list,'res'=>$res,'ad'=>$ad]);
        }
    }
    
}