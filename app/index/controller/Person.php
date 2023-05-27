<?php
namespace app\index\controller;
use think\facade\Db;/**加载数据库**/
use lib\Rule;/**无限极分类扩展类库**/
use think\facade\View;
use app\BaseController;
use think\facade\Session;
class Person extends BaseController
{
    public function index()
    {
        return view('index');
    }
}