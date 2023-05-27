<?php
namespace app\backend\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\captcha\facade\Captcha;


 

class Comment extends Common
{
    
	 
	
	public function menu(){
		
	}
	
	public function index(){
		if (request()->isPost()){
			$data=request()->param();/**获取post参数**/
			$draw=$data['draw'];/**获取Datatables发送的参数 必要 这个值会直接返回给前台**/
			$order_column=$data['order']['0']['column'];/**从哪一列开始排序，默认从0开始**/
			$order_dir=$data['order']['0']['dir'];/**排序ase desc 升序或者降序**/
			/*******拼接mysql*****/
			$orderSql = "";
			if(isset($order_column)){
				$i = intval($order_column);
					switch($i){
					/**插件默认从0开始排序 导致第一行禁用排序失效**/
					/***改进 从第二行开始排序***/
					case 0;$orderSql = " order by id ".$order_dir;break;
					case 1;$orderSql = " order by blog_id ".$order_dir;break;
					case 2;$orderSql = " order by create_time ".$order_dir;break;
					default;$orderSql = '';
				}
			}
			
		/**搜索条件**/	
		$search=$data['search']['value'];/**获取前台传过来的过滤条件**/
		/**分页参数**/
		$start=$data['start'];/**从多少开始**/
		$length=$data['length'];/**数据长度**/
		
		$limitSql = '';
		$limitFlag = isset($start) && $length != -1 ;
		if ($limitFlag ) {
			$limitSql = " LIMIT ".intval($start).", ".intval($length);
		}
		
		/**定义查询数据总记录数sql**/
		$sumSql = "SELECT count(id) as sum FROM comm";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" where (id LIKE '%".$search."%'  or con LIKE '%".$search."%')";
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select id,con,uid,blog_id,pid,create_time,status from comm";
		$v =[];
		$dataResult = Db::query($totalResultSql.$sumSqlWhere.$orderSql.$limitSql);
		foreach($dataResult as $key=>$val){
			$v[$key]['id']=$val['id'];
			$v[$key]['con']=$val['con'];
			$v[$key]['uid']=$val['uid'];
			$v[$key]['blog_id']=$val['blog_id'];
			$v[$key]['pid']=$val['pid'];
			$v[$key]['status']=$val['status']==1?'启用':'禁用';
			$v[$key]['create_time']=date('Y-m--d H:i:s',$val['create_time']);
			}

		/**Output 包含的是必要的**/
		echo json_encode(array(
		"draw" => intval($draw),
		"recordsTotal" => intval($recordsTotal),
		"recordsFiltered" => intval($recordsFiltered),
		"data" => $v
		),JSON_UNESCAPED_UNICODE);
		}else{
			return view('index');
		}
	}
	
	
	
	public function update(){
		if (request()->isPost()){
            //发生内容改变 post有数据过来
			$id=input('post.id');
            $data=[
                
            'status'=>input('post.status'),
            'create_time'=>time(),
            ];
            
            
            $res = Db::name('comm')->where(['id'=>$id])->update($data);
            if($res){
                echo "true";
                exit;
            }else{
                echo "false";
                exit;
            }
		//否则  显示旧内容	
		}else{
            //get数据
			$id=input('id');
			
			$info=Db::table('comm')->field('id,status')->where(['id'=>$id])->find();
			 
			return view('update', ['info' =>$info]);
		}
	}
}