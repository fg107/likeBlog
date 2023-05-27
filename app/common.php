<?php
// 应用公共文件
 function foo($n) {
  return date('Y-m-d', strtotime("-$n day"));
}

/*****判断访问设备*****/
function is_mobile()
{
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        
        if($is_iphone){
              return  '1';
        }
        if($is_android){
              return  '1';
        }
        if($is_ipad){
              return  '1';
        }
		if($is_pc){
              return  '0';
        } 
        if($is_mac){
              return  '0';
        }
}




/**
 * 计算两个时间戳之间相差的日时分秒
 * @param $date1 开始时间
 * @param $date2 结束时间
 * @return array
 */
//功能：计算两个时间戳之间相差的日时分秒
//$begin_time  开始时间戳
//$end_time 结束时间戳
function timediff($begin_time,$end_time)
{
      if($begin_time < $end_time){
         $starttime = $begin_time;
         $endtime = $end_time;
      }else{
         $starttime = $end_time;
         $endtime = $begin_time;
      }

      //计算天数
      $timediff = $endtime-$starttime;
      $days = intval($timediff/86400);
      //计算小时数
      $remain = $timediff%86400;
      $hours = intval($remain/3600);
      //计算分钟数
      $remain = $remain%3600;
      $mins = intval($remain/60);
      //计算秒数
      $secs = $remain%60;
      $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
      return $res;
}


//获取访客ip
 function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

function findCityByIpxt($ip){
	 // http://restapi.amap.com/v3/ip?key=be2c992f8179c762dc074f0fef853960&ip  高德地圖接口
    $content = file_get_contents("http://restapi.amap.com/v3/ip?key=be2c992f8179c762dc074f0fef853960&ip=".$ip);
    return json_decode($content,true);
  
 }
 
 
 //中文替换成* 为要替换成的字符串 start为开始的字符位置默认0开始 len为替换的长度
 function substr_replace_cn($string, $repalce = '*',$start = 0,$len = 0) {
    $count = mb_strlen($string, 'UTF-8'); //此处传入编码，建议使用utf-8。此处编码要与下面mb_substr()所使用的一致
    if(!$count) { return $string; }
    if($len == 0){
        $end = $count;  //传入0则替换到最后
    }else{
        $end = $start + $len;       //传入指定长度则为开始长度+指定长度
    }
    $i = 0;
    $returnString = '';
    while ($i < $count) {        //循环该字符串
        $tmpString = mb_substr($string, $i, 1, 'UTF-8'); // 与mb_strlen编码一致
        if ($start <= $i && $i < $end) {
            $returnString .= $repalce;
        } else {
            $returnString .= $tmpString;
        }
        $i ++;
    }
    return $returnString;
}




/***
*跳转函数
*  $url       跳转地址
*  
***/
function jumpTo($url){
echo "<script type='text/javascript'>";
echo "location.href='".$url."'";
echo "</script>";
}

/***
*
*返回上一步
*$parma 提示信息
***/
function historyTo($parma){
	echo"<script>alert('" . $parma . "');history.go(-1);</script>";  
}
// history.go(-1);跳轉回上一頁；