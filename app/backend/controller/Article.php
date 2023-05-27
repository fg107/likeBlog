<?php
namespace app\backend\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\captcha\facade\Captcha;


use app\backend\validate\Articles;
use think\exception\ValidateException;
use lib\Rule;/**无限极分类扩展类库**/

class Article extends Common
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
					case 1;$orderSql = " order by a.id ".$order_dir;break;
					case 2;$orderSql = " order by a.title ".$order_dir;break;
					case 3;$orderSql = " order by c.catname ".$order_dir;break;
					case 4;$orderSql = " order by a.attr ".$order_dir;break;
					case 5;$orderSql = " order by a.create_time ".$order_dir;break;
					case 6;$orderSql = " order by a.status ".$order_dir;break;
					case 7;$orderSql = " order by a.sort ".$order_dir;break;
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
		$sumSql = "SELECT count(id) as sum FROM article as a where a.del=0";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" and (a.id LIKE '%".$search."%'  or a.title LIKE '%".$search."%')";
		
		
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select a.id,a.title,a.keyword,a.preface,a.description,a.content,a.create_time,a.sort,a.del,a.status,a.catid,a.thumb,c.catid,c.catname,group_concat(attr.id) as attrid,group_concat(attr.name) as attrname,group_concat(attr.color) as attrcolor  from article as a left join article_attr as at on at.arid = a.id left join attr as attr on attr.id = at.atid left join cate as c on c.catid = a.catid where a.del=0";
		
		$group=" group by a.id";
		$v =[];
		
		$dataResult = Db::query($totalResultSql.$sumSqlWhere.$group.$orderSql.$limitSql);
		
		foreach($dataResult as $key=>$val){
			$v[$key]['id']=$val['id'];
			$v[$key]['title']=$val['title'];
			$v[$key]['keyword']=$val['keyword'];
			$v[$key]['preface']=$val['preface'];
			$v[$key]['description']=$val['description'];
			$v[$key]['content']=$val['content'];
			$v[$key]['status']=$val['status']==1?'启用':'禁用';
			$v[$key]['create_time']=date('Y-m--d H:i:s',$val['create_time']);
			$v[$key]['catname']=$val['catname'];
			$v[$key]['thumb']=json_decode($val['thumb']);
			$v[$key]['sort']=$val['sort'];
			$v[$key]['del']=$val['del'];
			foreach(explode(',',$val['attrid']) as $k=>$t){  /**中间表属性数据重新组合----**/
				$v[$key]['attr'][$k]['id'] = $t;
				$v[$key]['attr'][$k]['name'] = explode(',',$val['attrname'])[$k];
				$v[$key]['attr'][$k]['color'] = explode(',',$val['attrcolor'])[$k];
				}/**属性数据重新组合**/
				
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
		
		$files = request()->file();
		$savename = [];
		foreach($files as $file){
        $savename[] = \think\facade\Filesystem::disk('public')->putFile( 'files', $file);
	 
		echo json_encode(str_replace('\\','/',$savename));/**转义**/
		exit;
	 
		}
		
	}
	
	
	
	
	public function add(){
		if (request()->isPost()){
			$atid=input('post.atid/a');/**属性**/
			$thumb=json_encode(input('post.thumb/a'));/**图片以字段方式存储到数据库**/
			$data=[
				'catid'=>input('post.catid'),
				'title'=>input('post.title'),
				'keyword'=>input('post.keyword'),
				'preface'=>input('post.preface'),
				'description'=>input('post.description'),
				'content'=>input('content'),
				'thumb'=>$thumb,/**图片以字段方式存储到数据库**/
				'status'=>input('post.status'),
				'sort'=>input('post.sort'),
				'create_time'=>time(),
				'link'=>input('post.link')
			];
			try {
			$result = validate(Articles::class)->batch(true)->check([
			'title'  => input('post.title')
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$id = Db::name('article')->insertGetId($data);
				if($id){
					/**关联插入属性表**/
					if(!empty($atid)){
					$sql="insert into `article_attr` (arid,atid) VALUES";  //像属性中间表插入数据
					foreach($atid as $v){    //如果选中多个属性传过来的的是一个一维数组 
					$sql.="("."$id".",".$v."),";  //将循环的一维数组数据添加的mysql语句中
					}
					$sql=rtrim($sql,',');  //清除，
					Db::execute($sql);
					//print_r($sql);
					
					}
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
			$res=Db::table('cate')->alias('c')->join('models m','c.mid = m.id')->field('c.catid,c.pid,c.catname,c.title,c.status,c.create_time,c.thumb,c.sort,c.mid,m.name')->select();
			$cates=Rule::Cate($res);
			
			$cate=[];
			foreach($cates as $key=>$val){
			$cate[$key]['catid']=$val['catid'];
			$cate[$key]['catname']=$val['catname'];
			$cate[$key]['title']=$val['title'];
			$cate[$key]['pid']=$val['pid'];
			$cate[$key]['sort']=$val['sort'];
			$cate[$key]['lev']=$val['lev'];
			$cate[$key]['thumb']=json_decode($val['thumb']);
			$cate[$key]['status']=$val['status']==1?'启用':'禁用';
			$cate[$key]['create_time']=date('Y-m--d H:i:s',$val['create_time']);
			switch($cate[$key]['lev']){  /******添加层级*******/
					case 1;$cate[$key]['lev'] = " ";break;
					case 2;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;";break;
					case 3;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 4;$cate[$key]['lev']= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 5;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 6;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 7;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					default;$cate[$key]['lev'] = '';
				}
			}
			
			//print_r($cate);
			//exit;
			
			$attr =Db::table('attr')->order('id','asc')->select();
			return view('add',['cate'=>$cate,'attr'=>$attr]);
		}
	}
	
	public function update(){
		if (request()->isPost()){
			$id=input('post.id');
			$atid=input('post.atid/a');
			
			$thumb=json_encode(input('post.thumb/a'));/**图片以字段方式存储到数据库**/
			 
			$rest=Db::table('article')->field('thumb')->where(['id'=>$id])->find();
			$pic=json_decode($rest['thumb']);
			if(empty($thumb)||$thumb=='null'||$thumb==''){ /**判断提交过来的图片字段是否为空 如果为空则表示没有上传新的文件 不处理thumb字段**/
				$data=[
				'catid'=>input('post.catid'),
				'title'=>input('post.title'),
				'keyword'=>input('post.keyword'),
				'preface'=>input('post.preface'),
				'description'=>input('post.description'),
				'content'=>input('content'),
				'status'=>input('post.status'),
				'sort'=>input('post.sort'),
				'create_time'=>time(),
				'link'=>input('post.link')
				];
			}else{  /**如果上传了新文件则要覆盖掉原来的文件**/
				$data=[
				'catid'=>input('post.catid'),
				'title'=>input('post.title'),
				'keyword'=>input('post.keyword'),
				'preface'=>input('post.preface'),
				'description'=>input('post.description'),
				'content'=>input('content'),
				'thumb'=>$thumb,/**图片以字段方式存储到数据库**/
				'status'=>input('post.status'),
				'sort'=>input('post.sort'),
				'create_time'=>time(),
				'link'=>input('post.link')
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
			$result = validate(Articles::class)->batch(true)->check([
			'title'  => input('post.title')
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				
				$res = Db::name('article')->where(['id'=>$id])->update($data);
				if($res){
					
					if(!empty($atid)){
							
							$sqlattr="delete from article_attr  where arid='".$id."'";
							$res=Db::execute($sqlattr);
							
							$sql="insert into `article_attr` (arid,atid) VALUES";  //像属性中间表插入数据
						 foreach($atid as $v){    //如果选中多个属性传过来的的是一个一维数组 
							 $sql.="(".$id.",".$v."),";  //将循环的一维数组数据添加的mysql语句中
						 }
						 $sql=rtrim($sql,',');  //清除，
						 Db::execute($sql);
						//print_r($sql);
						}else{ /**没有选择属性清除原来的属性**/
							$sqlattr="delete from article_attr  where arid='".$id."'";
							$res=Db::execute($sqlattr);
						}
					
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
			$res=Db::table('cate')->alias('c')->join('models m','c.mid = m.id')->field('c.catid,c.pid,c.catname,c.title,c.status,c.create_time,c.thumb,c.sort,c.mid,m.name')->select();
			$cates=Rule::Cate($res);
			
			$cate=[];
			foreach($cates as $key=>$val){
			$cate[$key]['catid']=$val['catid'];
			$cate[$key]['catname']=$val['catname'];
			$cate[$key]['title']=$val['title'];
			$cate[$key]['pid']=$val['pid'];
			$cate[$key]['sort']=$val['sort'];
			$cate[$key]['lev']=$val['lev'];
			$cate[$key]['thumb']=json_decode($val['thumb']);
			$cate[$key]['status']=$val['status']==1?'启用':'禁用';
			$cate[$key]['create_time']=date('Y-m--d H:i:s',$val['create_time']);
			switch($cate[$key]['lev']){  /******添加层级*******/
					case 1;$cate[$key]['lev'] = " ";break;
					case 2;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;";break;
					case 3;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 4;$cate[$key]['lev']= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 5;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 6;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					case 7;$cate[$key]['lev'] = " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";break;
					default;$cate[$key]['lev'] = '';
				}
			}
			
			
			
			$attr =Db::table('attr')->order('id','asc')->select();
			$list=Db::table('article')->alias('a')->join('article_attr at','a.id = at.arid','left')->join('attr attr','attr.id = at.atid','left')->join('cate c','a.catid = c.catid','left')->field('a.id,a.title,a.keyword,a.preface,a.description,a.content,a.create_time,a.sort,a.del,a.status,a.catid,a.thumb,a.link,c.catname,group_concat(attr.id) as attrid')->where(['a.id'=>$id])->group('a.id')->find();
			
			$val=[];
			$val=[
				'id'=>$list['id'],
				'title'=>$list['title'],
				'preface'=>$list['preface'],
				'keyword'=>$list['keyword'],
				'description'=>$list['description'],
				'content'=>$list['content'],
				'create_time'=>$list['create_time'],
				'sort'=>$list['sort'],
				'del'=>$list['del'],
				'status'=>$list['status'],
				'catid'=>$list['catid'],
				'link'=>$list['link'],
				'thumb'=>json_decode($list['thumb']),
				'catname'=>$list['catname'],
				'attrid'=>explode(',',$list['attrid']),
			];
			return view('update',['cate'=>$cate,'attr'=>$attr,'info'=>$val]);
		}
	}
	
	public function del(){
		$id=input('id');
		$res=Db::table('article')->where('id',$id)->field('thumb')->find(); 
		$thumbs=json_decode($res['thumb']);
		//print_r($thumbs);
		if(!empty($thumbs)){
			foreach($thumbs as $thumb){
				$file=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$thumb;
				if(file_exists($file)){
					unlink('uploads/'.$thumb);
				}
				
			}
		}
		
		$sql="delete a,at from article a left join article_attr at on a.id=at.arid where a.id='".$id."'";
		$rest=Db::execute($sql);
			if($rest!==false){
				echo "true";
			}else{
				echo "false";
			}
		
	}
	
	
	/**标题重复检测**/
	public function checktitle(){
		$title=input('post.title');
		$res=Db::name('article')->where('title',$title)->column('title'); /**查询标题是否存在**/
		if($res){
			echo "false";
			exit;
		}else{
			echo "true";
			exit;
		}
		
	}
	
	
	/**排序**/
	public function sorts(){
		$id=input('post.id');
		$sort=input('post.sort');
		$res=Db::name('article')->update(['sort' =>$sort,'id'=>$id]);
		echo json_encode($res);
		exit;
	}
	
	/**datables排序前后对比**/
	public function sortcomp(){
		$id=input('post.id');
		$sort=Db::name('article')->where('id',$id)->value('sort');
		echo json_encode($sort);
		exit;
	}
	
	
	/**移到回收站/还原**/
	public function totrach(){
		
		$id=input('post.id');
		$type=input('post.type');
		$res=Db::name('article')->where('id',$id)->update(['del' =>$type]);
		if($res){
			echo "true";
			exit;
		}else{
			echo "false";
			exit;
		}
	}
	
	
	/**批量移到回收站**/
	public function totrachall(){
		$id=input('post.ids/a');
		if(empty($id)){
			echo historyTo('请选择需要移除的文章!');
			exit;
		}
		$ids=implode(',',$id);
		
		$sql="update article set del='1' where id in($ids)";
		$res=Db::execute($sql);
		if($res){
			echo jumpTo('/article/index');
			exit;
		}else{
			echo jumpTo('/article/index');
			exit;
		}
		
	}
    
	/**批量还原**/
	public function backtrachall(){
		$id=input('post.ids/a');
		if(empty($id)){
			echo historyTo('请选择需要移除的文章!');
			exit;
		}
		$ids=implode(',',$id);
		
		//print_r($ids);
		//exit;
		$sql="update article set del='0' where id in($ids)";
		$res=Db::execute($sql);
		if($res){
			echo jumpTo('/article/trach');
			exit;
		}else{
			//echo jumpTo('/article/trach');
			echo historyTo('操作失败!');
			exit;
		}
		
	}
	
	
	
	
	/**回收站**/
	public function trach(){
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
					case 1;$orderSql = " order by a.id ".$order_dir;break;
					case 2;$orderSql = " order by a.title ".$order_dir;break;
					case 3;$orderSql = " order by c.catname ".$order_dir;break;
					case 4;$orderSql = " order by a.attr ".$order_dir;break;
					case 5;$orderSql = " order by a.create_time ".$order_dir;break;
					case 6;$orderSql = " order by a.status ".$order_dir;break;
					case 7;$orderSql = " order by a.sort ".$order_dir;break;
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
		$sumSql = "SELECT count(id) as sum FROM article as a where a.del=1";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" and (a.id LIKE '%".$search."%'  or a.title LIKE '%".$search."%')";
		
		
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select a.id,a.title,a.keyword,a.preface,a.description,a.content,a.create_time,a.sort,a.del,a.status,a.catid,a.thumb,c.catid,c.catname,group_concat(attr.id) as attrid,group_concat(attr.name) as attrname,group_concat(attr.color) as attrcolor  from article as a left join article_attr as at on at.arid = a.id left join attr as attr on attr.id = at.atid left join cate as c on c.catid = a.catid where a.del=1";
		
		$group=" group by a.id";
		$v =[];
		
		$dataResult = Db::query($totalResultSql.$sumSqlWhere.$group.$orderSql.$limitSql);
		
		foreach($dataResult as $key=>$val){
			$v[$key]['id']=$val['id'];
			$v[$key]['title']=$val['title'];
			$v[$key]['keyword']=$val['keyword'];
			$v[$key]['preface']=$val['preface'];
			$v[$key]['description']=$val['description'];
			$v[$key]['content']=$val['content'];
			$v[$key]['status']=$val['status']==0?'启用':'禁用';
			$v[$key]['create_time']=date('Y-m--d H:i:s',$val['create_time']);
			$v[$key]['catname']=$val['catname'];
			$v[$key]['thumb']=json_decode($val['thumb']);
			$v[$key]['sort']=$val['sort'];
			$v[$key]['del']=$val['del'];
			foreach(explode(',',$val['attrid']) as $k=>$t){  /**中间表属性数据重新组合----**/
				$v[$key]['attr'][$k]['id'] = $t;
				$v[$key]['attr'][$k]['name'] = explode(',',$val['attrname'])[$k];
				$v[$key]['attr'][$k]['color'] = explode(',',$val['attrcolor'])[$k];
				}/**属性数据重新组合**/
				
			}
		
		
		/**Output 包含的是必要的**/
		echo json_encode(array(
		"draw" => intval($draw),
		"recordsTotal" => intval($recordsTotal),
		"recordsFiltered" => intval($recordsFiltered),
		"data" => $v
		),JSON_UNESCAPED_UNICODE);

		}else{
			return view('trach');
		}
		
	}
	
}
