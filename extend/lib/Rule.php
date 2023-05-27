<?php
namespace lib; 
/*
无限极分类 的使用
*/
class Rule{
		 
		
		/***
		无限极分类之------规则递归
		*/
		static public function RuleList($rule,$pid=0,$lev=1)
		{
			$arr=array();
			foreach($rule as $v){
				if (!isset($v['catid']) || empty($v['catid'])) 
				{
					$v['catid'] = $v['id'];
				}
				if($v['pid']==$pid)
				{
					$v['lev']=$lev;
					$arr[]=$v;
					$arr=array_merge($arr,self::RuleList($rule,$v['catid'],$lev+1));
				}
			}
			return $arr;
		}
		/**
		无限极分类之------循环顶级导航和子导航方法 组合多维数组
		**/
		static public function 	Rulelayer($rule,$pid=0){
			//生命新的空数组，存放组合的多维数组
			$arr=array();
			foreach($rule as $v){
				

				if (!isset($v['catid']) || empty($v['catid'])) 
				{
					$v['catid'] = $v['id'];
				}
				
				if($v['pid']==$pid){
					$v['child']=self::Rulelayer($rule,$v['catid']);
					$arr[]=$v;
				}
			}
			return $arr;
		}
		
		static public function 	Rulelayers($rule,$pid=0){
			$arr=array();
			foreach($rule as $v){
				if($v['pid']==$pid){
					$v['child']=self::Rulelayers($rule,$v['id']);
					$arr[]=$v;
				}
			}
			return $arr;
		}
		
		/**
		无限极分类之------面包屑实现方法------通过传递子分类的id查找其所有的父类
		**/
		static public function getParents($rule,$catid){
			$arr=array();
			foreach($rule as $v){
				if($v['catid']==$catid){
					$arr[]=$v;
					//$arr=array_merge($arr,self::getParents($rule,$v['pid']));  //获取子分类的所有父分类
					$arr=array_merge(self::getParents($rule,$v['pid']),$arr); //替换下位置实现面包屑导航
					
				}
			}
			return $arr;
		}

		
		 
	 
		 


 
		
}
?>