<?php
namespace app\backend\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\captcha\facade\Captcha;


use app\backend\validate\Fsmsgs;
use think\exception\ValidateException;

class Fsmsg extends Common
{
    
	/**tp5input说明start****/
	/*变量修饰符
	input函数支持对变量使用修饰符功能，可以更好的过滤变量。
	用法如下：
	input('变量类型.变量名/修饰符');
	或者
	request()->变量类型('变量名/修饰符');
	例如：
	input('get.id/d');
	input('post.name/s');
	input('post.ids/a');
	request()->get('id/d');
	ThinkPHP5.0版本默认的变量修饰符是/s，如果需要传入字符串之外的变量可以使用下面的修饰符，包括：
	修饰符 作用
	s 强制转换为字符串类型
	d 强制转换为整型类型
	b 强制转换为布尔类型
	a 强制转换为数组类型
	f 强制转换为浮点类型
	如果你要获取的数据为数组，请一定注意要加上 /a 修饰符才能正确获取到。*/
	/**tp5input说明end****/
	
	
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
					case 1;$orderSql = " order by name ".$order_dir;break;
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
		$sumSql = "SELECT count(id) as sum FROM fsmsg";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" where (id LIKE '%".$search."%'  or name LIKE '%".$search."%')";
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select id,name,mobile,content,create_time,status from fsmsg";
		$v =[];
		$dataResult = Db::query($totalResultSql.$sumSqlWhere.$orderSql.$limitSql);
		foreach($dataResult as $key=>$val){
			$v[$key]['id']=$val['id'];
			$v[$key]['name']=$val['name'];
			$v[$key]['mobile']=$val['mobile'];
			$v[$key]['content']=$val['content'];
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
	
	
	
	public function add(){
		if (request()->isPost()){
			$data=[
				'name'=>input('post.name'),
				'mobile'=>input('post.mobile'),
				'content'=>input('post.content'),
				'from_ip'=>get_client_ip(),
				'status'=>input('post.status'),
				'create_time'=>time(),
				
			];
			try {
			$result = validate(Fsmsgs::class)->batch(true)->check([
			'name'  => input('post.name'),
			
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$id = Db::name('fsmsg')->insertGetId($data);
				if($id){
					echo "true";
					exit;
				}else{
					echo "false";
					exit;
				}
			}
			
			} catch (ValidateException $e) {
            // 验证失败 输出错误信息
				dump($e->getError());
			}	
		}else{
			return view('add');
		}
	}
	
	public function update(){
		if (request()->isPost()){
			$id=input('post.id');
				$data=[
				'name'=>input('post.name'),
				'mobile'=>input('post.mobile'),
				'content'=>input('post.content'),
				'from_ip'=>get_client_ip(),
				'status'=>input('post.status'),
				'create_time'=>time(),
				];
			try {
			$result = validate(Fsmsgs::class)->batch(true)->check([
			'name'  => input('post.name'),
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				
				$res = Db::name('fsmsg')->where(['id'=>$id])->update($data);
				if($res){
					echo "true";
					exit;
				}else{
					echo "false";
					exit;
				}
			}
			
			} catch (ValidateException $e) {
            // 验证失败 输出错误信息
				dump($e->getError());
			}	
		}else{
			$id=input('id');
			
			$infos=Db::table('fsmsg')->where(['id'=>$id])->find();
			$info=[
				'id'=>$infos['id'],
				'name'=>$infos['name'],
				'mobile'=>$infos['mobile'],
				'content'=>$infos['content'],
				'from_ip'=>get_client_ip(),
				'status'=>$infos['status'],
				'create_time'=>$infos['create_time']
			];
			return view('update', ['info' =>$info]);
		}
	}
	
	public function del(){
		$id=input('id');
		$sql="delete  from fsmsg where id='".$id."'";
		$res=Db::execute($sql);
			if($res!==false){
				echo "true";
				exit;
			}else{
				echo "false";
				exit;
			}
		
	}
	
	

    
	
	
}
