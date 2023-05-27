<?php
namespace app\backend\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\captcha\facade\Captcha;


use app\backend\validate\Cates;
use think\exception\ValidateException;
use lib\Rule;/**无限极分类扩展类库**/

class Cate extends Common
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
		$list = Db::table('cate')->order('catid asc');
			 
		//$list=Db::table('cate')->order('catid asc')->paginate(20);
		// 获取分页显示
		/*$res=Db::table('cate')->alias('c')->join('models m','c.mid = m.mid')->field('c.catid,c.pid,c.catname,c.title,c.status,c.create_time,c.mid,m.moname')->paginate(1000);
		$list=Rule::Cate($res);
		$this->assign('list', $list);*/
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
					case 0;$orderSql = " order by c.catid ".$order_dir;break;
					case 1;$orderSql = " order by c.catname ".$order_dir;break;
					case 2;$orderSql = " order by c.sort ".$order_dir;break;
					
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
		$sumSql = "SELECT count(catid) as sum FROM cate as c";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" where (c.catid LIKE '%".$search."%'  or c.catname LIKE '%".$search."%')";
		
		
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select c.catid,c.pid,c.catname,c.title,c.status,c.sort,c.thumb,c.create_time,c.mid,m.name as moname from cate as c left join models as m on m.id = c.mid";
		
		$group=" group by c.catid";
		$v =[];
		
		$res = Db::query($totalResultSql.$sumSqlWhere.$group.$orderSql.$limitSql);
		$dataResult=Rule::Cate($res);
		
		foreach($dataResult as $key=>$val){
			$v[$key]['catid']=$val['catid'];
			$v[$key]['catname']=$val['catname'];
			$v[$key]['moname']=$val['moname'];
			$v[$key]['title']=$val['title'];
			$v[$key]['pid']=$val['pid'];
			$v[$key]['sort']=$val['sort'];
			$v[$key]['lev']=$val['lev'];
			$v[$key]['thumb']=json_decode($val['thumb']);
			$v[$key]['status']=$val['status']==1?'启用':'禁用';
			$v[$key]['create_time']=date('Y-m--d',$val['create_time']);
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
	
	
	/**文件上传**/
	public function upload(){
		//获取文件上传对象
		$files = request()->file();
		
		if (isset($files)&&!empty($files)) {
			//文件上传后本地服务器的存储路径
			$savename = [];
			foreach($files as $file){
				$savename[] = \think\facade\Filesystem::disk('public')->putFile( 'files', $file);
				 
				 
				echo json_encode(str_replace('\\','/',$savename));/**转义**/
				exit;
			}
		}
		
	}
	
	
	/*******分类名称检测*******/
	public function checkcatname(){
		
		$catname=input('post.catname');
		$res=Db::name('cate')->where('catname',$catname)->column('catname'); /**查询类型名是否存在**/
		
		if($res){
			echo "false";
			exit;
		}else{
			echo "true";
			exit;
		}
		
	}
	
	
	
	
	public function add(){
		if (request()->isPost()){
			$thumb=json_encode(input('post.thumb/a'));
			$data=[
				'catname'=>input('post.catname'),
				'ftitle'=>input('post.ftitle'),
				'title'=>input('post.title'),
				'keyword'=>input('post.keyword'),
				'description'=>input('post.description'),
				'sort'=>input('post.sort'),
				'status'=>input('post.status'),
				'mid'=>input('post.mid'),
				'pid'=>input('post.pid'),
				'create_time'=>time(),
				'thumb'=>$thumb,/**图片以字段方式存储到数据库**/
			];
			try {
			$result = validate(Cates::class)->batch(true)->check([
			'catname'  => input('post.catname')
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$id = Db::name('cate')->insertGetId($data);
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
			$models =Db::table('models')->order('id','asc')->select();
			 
			$pid=input('pid')?input('pid'):'0'; /**获取pid**/
			return view('add',['models'=>$models,'pid'=>$pid]);
		}
	}
	
	public function update(){
		if (request()->isPost()){
			$catid=input('post.catid');
			//从前端提交过来的图片数据
			$a = input('post.thumb/a');
			$thumb=json_encode(input('post.thumb/a'));
			
		 
			

			$rest=Db::table('cate')->field('thumb')->where(['catid'=>$catid])->find();
			$pic=json_decode($rest['thumb']);
			
			if(empty($thumb)||$thumb=='null'||$thumb==''){ /**判断提交过来的图片字段是否为空 如果为空则表示没有上传新的文件 不处理thumb字段**/
				$data=[
				'catname'=>input('post.catname'),
				'ftitle'=>input('post.ftitle'),
				'title'=>input('post.title'),
				'keyword'=>input('post.keyword'),
				'description'=>input('post.description'),
				'sort'=>input('post.sort'),
				'status'=>input('post.status'),
				'mid'=>input('post.mid'),
				'create_time'=>time(),
				];
				
			}else{  /**如果上传了新文件则要覆盖掉原来的文件**/
				$data=[
				'catname'=>input('post.catname'),
				'ftitle'=>input('post.ftitle'),
				'title'=>input('post.title'),
				'keyword'=>input('post.keyword'),
				'description'=>input('post.description'),
				'sort'=>input('post.sort'),
				'status'=>input('post.status'),
				'mid'=>input('post.mid'),
				'thumb'=>$thumb,/**图片以字段方式存储到数据库**/
				'create_time'=>time()
				];	
				
				/**删除原来保存的图片**/
				if(!empty($pic)){
					foreach($pic as $pics){
					$file=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$pics;
						if(file_exists($file)){
						unlink('uploads/'.$pics);
						}
					}
				}
			}
			
			
			try {
			$result = validate(Cates::class)->batch(true)->check([
			'catname'  => input('post.catname')
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				
				$res = Db::name('cate')->where(['catid'=>$catid])->update($data);
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
			$catid=input('catid');
			$totalResultSql = "select c.catid,c.catname,c.ftitle,c.title,c. 	keyword,c.description,c.status,c.sort,c.mid,c.thumb,c.create_time,c.pid,m.name as moname from cate as c left join models as m on m.id = c.mid";

			$group=" group by c.catid";
			$sumSqlWhere=" where c.catid='".$catid."'";
			
			//print_r($totalResultSql.$sumSqlWhere.$group);
			//exit;
			
			$dataResult = Db::query($totalResultSql.$sumSqlWhere.$group);
		
			$v =[];
			foreach($dataResult as $key=>$val){
				 
			$v['catid']=$val['catid'];
			$v['catname']=$val['catname'];
			$v['ftitle']=$val['ftitle'];
			$v['title']=$val['title'];
			$v['keyword']=$val['keyword'];
			$v['description']=$val['description'];
			$v['pid']=$val['pid'];
			$v['sort']=$val['sort'];
			$v['moname']=$val['moname'];
			$v['mid']=$val['mid'];
			$v['thumb']=json_decode($val['thumb']);
			$v['status']=$val['status'];
			$v['create_time']=date('Y-m--d H:i:s',$val['create_time']);
			}
			 
			$models =Db::table('models')->order('id','asc')->select();
			 
			return view('update', ['info' =>$v,'models'=>$models]);
		}
	}
	
	public function del(){
		$catid=input('catid');
		
		$res=Db::table('cate')->where('pid',$catid)->select()->toArray(); /**获取子栏目数据如果存在子栏目数据则不能删除**/
		
		if(!empty($res)){
			echo "1";
			exit;
		}else{
			$res=Db::table('cate')->where('catid',$catid)->field('thumb')->find(); 
			$thumbs=json_decode($res['thumb']);
		
			/**同步删除文件**/
			if(!empty($thumbs)){
			foreach($thumbs as $thumb){
				$file=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$thumb;
				if(file_exists($file)){
				unlink('uploads/'.$thumb);
				}
				
				}
			}
			$sql="delete  from cate where catid='".$catid."'";
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
	
	

    
	
	
}
