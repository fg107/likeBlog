<?php
/***************author wfs*****************/
namespace app\backend\controller;
use think\facade\Db;/**加载数据库**/
use app\BaseController;
use think\facade\View;
use think\captcha\facade\Captcha;

use app\backend\validate\Authrule;
use app\backend\validate\Authgroup;
use app\backend\validate\Authusers;
use think\exception\ValidateException;
use lib\Rule;/**无限极分类扩展类库**/


class Auth extends Common
{
	
	/**控制菜单显示或隐藏**/
	public  function menu(){
		
	}
	
	/**************用户管理start*****************/
	
    public function index()
    {
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
					case 0;$orderSql = " order by u.uid ".$order_dir;break;
					case 1;$orderSql = " order by u.uname ".$order_dir;break;
					case 2;$orderSql = " order by u.create_time ".$order_dir;break;
					
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
		$sumSql = "SELECT count(uid) as sum FROM users as u";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" where (u.uid LIKE '%".$search."%'  or u.uname LIKE '%".$search."%')";
		
		
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select u.uid,u.uname,u.pwd,u.create_time,u.status as status,u.login_ip,gr.uid as gruid,gr.group_id,g.id as gid,g.title,g.status as gstatus,g.rules  from users as u left join auth_group_access as gr on gr.uid = u.uid left join auth_group as g on g.id = gr.group_id";
		
		$group=" group by u.uid";
		$v =[];
		
		$dataResult = Db::query($totalResultSql.$sumSqlWhere.$group.$orderSql.$limitSql);
		//print_r($dataResult);
		foreach($dataResult as $key=>$val){
			$v[$key]['uid']=$val['uid'];
			$v[$key]['uname']=$val['uname'];
			$v[$key]['title']=$val['title'];
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
	
	public function add()
    {
		if (request()->isPost()){
			$group_id=input('post.group_id');
			$data=[
				'uname'=>input('post.uname'),
				'pwd'=>password_hash(input('post.pwd'),PASSWORD_BCRYPT),/**加密**/
				'status'=>input('post.status'),
				'login_ip'=>request()->ip(),
				'create_time'=>time(),
			];
			
			try {
			
			$result=validate(Authusers::class)->scene('add')->check([
				'uname'  => input('post.uname'),
				'pwd' =>input('post.pwd'),
			]);
			

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				/**insertGetId方法新增数据并返回主键值**/
				$uid = Db::name('users')->insertGetId($data);
				if($uid){
						$res=[
						'uid'=>$uid,
						'group_id'=>$group_id,
						];
						$re=Db::name('auth_group_access')->insertGetId($res);
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
			$group =Db::table('auth_group')->order('id','asc')->where('status',1)->select();
			return view('add',['group'=>$group]);
		}
    }
	
	
	/**检测用户名是否重复**/
	public function checkuname(){
		$uname=input('post.uname');
		 /**查询用户名是否存在**/
		$res=Db::table('users')->where('uname',$uname)->column('uname');
		if($res){
			echo "false";
			exit;
		}else{
			echo "true";
			exit;
		}
		
	}
	
	public function update()
    {
		if (request()->isPost()){
			$uid=input('post.uid');
			$group_id=input('post.group_id');
			$uname=input('post.uname');
			$status=input('post.status');
			
			$data=[
				'uname'=>input('post.uname'),
				'status'=>input('post.status'),
				'login_ip'=>request()->ip(),
				'create_time'=>time(),
			];
			
			try {
			$result=validate(Authusers::class)->scene('update')->check([
				'uname'  => input('post.uname'),
			]);
			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$res = Db::name('users')->where(['uid'=>$uid])->update($data);
				if($res !==false){
					$re=Db::table('auth_group_access')->where('uid', $uid)->update(['uid' =>$uid,'group_id'=>$group_id]);
					if($re !==false){
						echo "true";
						exit;
					}else{
						echo "false";
						exit;
					}
					
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
			$uid=input("uid");
			
			//$info=UserModel::where('uid',$uid)->find();
			$group =Db::table('auth_group')->order('id','asc')->where('status',1)->select();
			/**关联查询获取数据**/
			$info=Db::table('users')->alias('u')->join('auth_group_access gr','u.uid = gr.uid')->join('auth_group g','gr.group_id = g.id')->field('u.uid,u.uname,u.pwd,u.create_time,u.status as status1,u.login_ip,gr.uid as gruid,gr.group_id,g.id as gid,g.title,g.status as status2,g.rules')->where(['u.uid'=>$uid])->find();/**用户--中间表--用户组**/
			return view('update',['info'=>$info,'group'=>$group]);
		}
    }
	
	public function del()
    {
		$uid=input('uid');
		$sql="delete u,gr from users u inner join auth_group_access gr on u.uid=gr.uid where u.uid='".$uid."'";
		$res=Db::execute($sql);
		if($res!==false){
		echo "true";
		exit;
		}else{
		echo "false";
		exit;
		}
    }
	
	public function resetpwd(){
		if (request()->isPost()){
			$uid=input('post.uid');
			$data=[
				'uid'=>input('post.uid'),
				'pwd'=>password_hash(input('post.pwd'),PASSWORD_BCRYPT),
			];
			
			try {
			$result=validate(Authusers::class)->scene('resetpwd')->check([
				'pwd'  => input('post.pwd'),
				'pwd_confirm' =>input('post.pwd_confirm'),
			]);
			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$res = Db::name('users')->where(['uid'=>$uid])->update($data);
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
			$uid=input('uid');
			return view('resetpwd',['uid'=>$uid]);
		}
	}
	
	
	/**************用户管理end*****************/
	
	
	
	
	
	
	/**************规则管理start*****************/
	
	public function rule()
    {
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
					case 2;$orderSql = " order by title ".$order_dir;break;
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
		$sumSql = "SELECT count(id) as sum FROM auth_rule";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" where (id LIKE '%".$search."%'  or title LIKE '%".$search."%')";
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select id,title,name,status,is_menu,pid from auth_rule";
		$v =[];
		$res = Db::query($totalResultSql.$sumSqlWhere.$orderSql.$limitSql);
		
		$dataResult=Rule::RuleList($res);/**递归**/
		foreach($dataResult as $key=>$val){
			$v[$key]['id']=$val['id'];
			$v[$key]['title']=$val['title'];
			$v[$key]['name']=$val['name'];
			$v[$key]['is_menu']=$val['is_menu']==1?'启用':'禁用';
			$v[$key]['lev']=$val['lev'];
			}
		
		/**Output 包含的是必要的**/
		echo json_encode(array(
		"draw" => intval($draw),
		"recordsTotal" => intval($recordsTotal),
		"recordsFiltered" => intval($recordsFiltered),
		"data" => $v
		),JSON_UNESCAPED_UNICODE);
		}else{
			return view('rule');
		}
    }
	
	
	/**新增**/
	public function addrule(){
		if (request()->isPost()){
			$data=[
				'name'=>input('post.name'),
				'title'=>input('post.title'),
				'is_menu'=>input('post.is_menu'),
				'pid'=>input('post.pid')
			];
			
			
			
			try {
			$result = validate(Authrule::class)->batch(true)->check([
			'title'  => input('post.title'),
			'name' =>input('post.name'),
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$id = Db::name('auth_rule')->insertGetId($data);
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
			$pid=input('pid')?input('pid'):'0'; /**获取pid**/
			return view('addrule',['pid'=>$pid]);
		}
	}
	
	
	
	
	
	/**编辑**/
	public function updaterule(){
		if (request()->isPost()){
			$id=input('post.id');
			$data=[
				'name'=>input('post.name'),
				'title'=>input('post.title'),
				'is_menu'=>input('post.is_menu')
			];
			
			try {
			$result = validate(Authrule::class)->batch(true)->check([
			'title'  => input('post.title'),
			'name' =>input('post.name'),
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				
				$res = Db::name('auth_rule')->where(['id'=>$id])->update($data);
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
			$info=Db::table('auth_rule')->where(['id'=>$id])->find();
			return view('updaterule', ['info' =>$info]);
		}
	}
	
	
	
	/**datables状态前后对比**/
	public function scomp(){
		$id=input('post.id');
		$is_menu=Db::table('auth_rule')->where('id',$id)->value('is_menu');
		echo json_encode($is_menu);
		exit;
	}
	
	/**修改状态**/
	public function stats(){
		$id=input('post.id');
		$is_menu=input('post.is_menu');
		$res=Db::table('auth_rule')->update(['is_menu' =>$is_menu,'id'=>$id]);
		echo json_encode($res);
		exit;
	}
	
	/**删除规则**/
	public function delrule(){
		$id=input('post.id');
		$res=Db::table('auth_rule')->where('pid',$id)->select()->toArray();/**获取子栏目数据如果存在子栏目数据则不能删除**/
		
		if(!empty($res)){
			echo '1';
			exit;
		}else{
			$sql="delete  from auth_rule where id='".$id."'";
			$re=Db::execute($sql);
			if($re!==false){
				echo "true";
				exit;
			}else{
				echo "false";
				exit;
			}
		}
	}
	
	/**************规则管理end*****************/
	
	
	
	
	
	
	
	
	
	/**************角色管理start*****************/
	
	public function group()
    {
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
		$sumSql = "SELECT count(id) as sum FROM auth_group";
		/*条件过滤后记录数 必要*/
		$recordsFiltered = 0;
		/*表的总记录数 必要*/
		$recordsTotal = 0;
		$resTotal = Db::query($sumSql);/**查询数据表记录总条数**/	
		foreach($resTotal as $key=>$val){
			$recordsTotal =  $val['sum'];/**获取记录总数赋值给变量**/
		}
		
		/**定义过滤条件查询过滤后的记录数sql**/
		$sumSqlWhere =" where (id LIKE '%".$search."%'  or title LIKE '%".$search."%')";
		/***根据搜索条件拼接mysql语句***/
		if(strlen($search)>0){/**如果是搜索查询**/
			$recordsFilteredResult = Db::query($sumSql.$sumSqlWhere);
			foreach($recordsFilteredResult as $k=>$vals){
				$recordsFiltered =  $vals['sum'];/**获取记录总数赋值给变量**/
			}
		}else{
			$recordsFiltered = $recordsTotal;
		}
		
		$totalResultSql = "select id,title,status,rules from auth_group";
		$v =[];
		$dataResult = Db::query($totalResultSql.$sumSqlWhere.$orderSql.$limitSql);
		foreach($dataResult as $key=>$val){
			$v[$key]['id']=$val['id'];
			$v[$key]['title']=$val['title'];
			$v[$key]['status']=$val['status']==1?'启用':'禁用';
			$v[$key]['rules']=$val['rules'];
			}

		/**Output 包含的是必要的**/
		echo json_encode(array(
		"draw" => intval($draw),
		"recordsTotal" => intval($recordsTotal),
		"recordsFiltered" => intval($recordsFiltered),
		"data" => $v
		),JSON_UNESCAPED_UNICODE);
		}else{
			return view('group');
		}
    }
	
	public function addgroup()
    {
		if (request()->isPost()){
			$rules=implode(',',$_POST['rules']);/**将传过来的数组转换成字符串**/
			$data=[
				
				'title'=>input('post.title'),
				'status'=>input('post.status'),
				'rules'=>$rules,
			];
			
			try {
			$result = validate(Authgroup::class)->batch(true)->check([
			'title'  => input('post.title'),
			
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$id = Db::name('auth_group')->insertGetId($data);
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
			$res =Db::table('auth_rule')->order('id','asc')->select();
			$rule=Rule::Rulelayers($res);
			return view('addgroup',['rule'=>$rule]);
		}
    }
	
	public function updategroup()
    {
		if (request()->isPost()){
			$id=input('post.id');
			$rul=input('rules/a');/**获取规则id数组**/
			$rules=implode(',',$rul);
			$data=[
				'title'=>input('post.title'),
				'status'=>input('post.status'),
				'rules'=>$rules
			];
			
			try {
			$result = validate(Authgroup::class)->batch(true)->check([
			'title'  => input('post.title'),
			
		    ]);

			if (true !== $result) {
		    // 验证失败 输出错误信息
				dump($result);
			}else{
				$res = Db::name('auth_group')->where(['id'=>$id])->update($data);
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
			$id=input("id");
			$info=Db::table('auth_group')->where(['id'=>$id])->find();
			$res =Db::table('auth_rule')->order('id','asc')->select();
			//$rule=Rule::RuleList($res);
			$rule=Rule::Rulelayers($res);
			//print_r($info['rules']);
			$rules=explode(',',$info['rules']);//取出规则的id 字符串 将字符串转换为数组 判断是否选中
			return view('updategroup', ['info' =>$info,'rule'=>$rule,'rules'=>$rules]);
		}
    }
	
	public function delgroup()
    {
		$id=input('post.id');
		
		$sql="delete  from auth_group where id='".$id."'";
		$res=Db::execute($sql);
			if($res!==false){
				echo "true";
				exit;
			}else{
				echo "false";
				exit;
			}
    }
	
	
	
	/**************角色管理end*****************/
}
