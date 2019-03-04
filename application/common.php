<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * ajax成功返回
 * created by:Mp_Lxj
 * @date:2018/11/2 22:52
 * @param string $msg
 * @param string $data
 * @return array
 */
function trueAjax($msg = '',$data = '')
{
	return ['status' => 1,'msg' => $msg,'data' => $data];
}

/**
 * ajax失败返回数据
 * created by:Mp_Lxj
 * @date:2018/11/2 22:52
 * @param string $msg
 * @param string $data
 * @return array
 */
function falseAjax($msg = '',$data = '')
{
	return ['status' => 0,'msg' => $msg,'data' => $data];
}

/**
 * 获取视频信息
 * created by:Mp_Lxj
 * @date:2018/12/21 22:46
 * @param $id
 * @return array
 */
function getCC($id)
{
//	$cc_config = \think\Config::get('cc_video');
//	$data = [
//		'userid' => $cc_config['uid'],
//		'videoid' => $id,
//		'format' => 'json'
//	];
//	$detail_url = 'http://spark.bokecc.com/api/video/v4' . '?' . queryStr($data);
//	$detail = json_decode(sendcurl($detail_url),true);

//	$data = [
//		'userid' => $cc_config['uid'],
//		'videoid' => $id,
//		'format' => 'json',
//		'playerid' => $cc_config['play_id'],
//		'player_width' => '100%',
//		'player_height' => '100%',
//		'auto_play' => 'true',
//		'mediatype' => 2
//	];
//	$play_url = 'http://spark.bokecc.com/api/video/playcode' . '?' . queryStr($data);
//	$play = json_decode(sendcurl($play_url),true);

//	$data = [
//		'userid' => $cc_config['uid'],
//		'videoid' => $id
//	];
//	$down_url = 'http://spark.bokecc.com/api/video/original' . '?' . queryStr($data);
//	$down = json_decode(sendcurl($down_url),true);
//	if(!$down){
//		return falseAjax('该视频不存在');
//	}

//	$result = [
//		'playcode' => $play['video']['playcode'],
//		'duration' => $detail['video']['duration'],
//		'down_url' => $down['video']['url']
//	];
	$param = \think\Request::instance()->param();

	$result = [
		'playcode' =>$id,
		'duration' => $param['duration'] ?: '',
		'down_url' => $id
	];
	return trueAjax('',$result);
}

/**
 * 获取视频播放时长
 * Created by：Mp_Lxj
 * @date 2019/1/9 13:50
 * @param $id
 * @return mixed|string
 */
function getVideoTime($id)
{
	$time = \think\Cache::get('video_time_' . $id);
	if(!$time){
		$cc_config = \think\Config::get('cc_video');
		$data = [
			'userid' => $cc_config['uid'],
			'videoid' => $id,
			'format' => 'json'
		];
		$detail_url = 'http://spark.bokecc.com/api/video/v4' . '?' . queryStr($data);
		$detail = json_decode(sendcurl($detail_url),true);
		$time = secToTime($detail['video']['duration']);
		\think\Cache::set('video_time_' . $id,$time);
	}
	return $time;
}

/**
 * 获取视频下载地址
 * Created by：Mp_Lxj
 * @date 2019/1/8 11:26
 * @param $id
 * @return mixed
 */
function getVideoUrl($id)
{
	$path = \think\Cache::get('video_path_' . $id);
	if(!$path){
		$today_time = strtotime('+1 day');
		$cache_time = $today_time - time();
		$cc_config = \think\Config::get('cc_video');
		$data = [
			'userid' => $cc_config['uid'],
			'videoid' => $id
		];
		$down_url = 'http://spark.bokecc.com/api/video/original' . '?' . queryStr($data);
		$down = json_decode(sendcurl($down_url),true);
		$path = $down['video']['url'];
		\think\Cache::set('video_path_' . $id,$path,$cache_time);
	}
	return $path;
}

/**
 * 生成请求字符
 * created by:Mp_Lxj
 * @date:2018/12/21 23:11
 * @param $data
 * @return string
 */
function queryStr($data)
{
	$cc_config = \think\Config::get('cc_video');
	ksort($data);
	$str = '';
	foreach($data as $k=>$v){
		if($str){
			$str .= '&' . $k . '=' . urlencode($v);
		}else{
			$str = $k . '=' . urlencode($v);
		}
	}
	$str .= '&time=' . time();

	$hash = strtoupper(md5($str . '&salt=' . $cc_config['key']));

	$str .= '&hash=' . $hash;
	return $str;
}

/**
 * curl请求
 * Created by：Mp_Lxj
 * @date 2018/11/5 9:35
 * @param $url
 * @param array $data
 * @param array $header
 * @param string $method
 * @param bool $ssl
 * @return mixed
 */
function sendCurl($url,$data=[],$header=[],$method='GET',$ssl=false)
{
	$ch = curl_init($url);
	curl_setopt($ch , CURLOPT_CUSTOMREQUEST , $method);  //设置请求方式为POST
	curl_setopt($ch , CURLOPT_POSTFIELDS , $data);  //设置请求发送参数内容,参数值为关联数组
	curl_setopt($ch , CURLOPT_HTTPHEADER , $header );  //设置请求报头的请求格式为json, 参数值为非关联数组
	curl_setopt($ch , CURLOPT_RETURNTRANSFER , true);
	if($ssl){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //服务器要求使用安全链接https请求时，不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}

	$result = curl_exec($ch);  //发送请求并获取结果

	curl_close($ch); //关闭curl
	return $result;
}


/**
 * 秒转换为时分秒
 * created by:Mp_Lxj
 * @date:2018/12/21 23:28
 * @param $times
 * @return string
 */
function secToTime($times){
	$result = '00:00';
	if ($times>0) {
		$hour = floor($times/3600);
		$minute = floor(($times-3600 * $hour)/60);
		if(strlen($minute) == 1){
			$minute = '0' . $minute;
		}
		$second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
		if(strlen($second) == 1){
			$second = '0' . $second;
		}
		$result = $minute.':'.$second;
		if($hour){
			$result = $hour . ':' . $result;
		}
	}
	return $result;
}

function getUrl()
{
	return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function phoneHide($phone)
{
	$head = substr($phone,0,3);
	$foot = substr($phone,7,4);
	return $head . '****' . $foot;
}


/**
 * 文件上传--递归上传、支持多图、单图上传
 * created by:Mp_Lxj
 * @date:2018/11/2 23:05
 * @param $file
 * @return array
 */
function uploadFile($file){
	$result = [];
	$config = [
		'size' => 10*1024*1024,
		'ext'  => 'jpg,png,gif,jpeg',
	];
	$savePath = date('Ymd',time());
	$path = ROOT_PATH.'public'.DS.'upload'.DS.$savePath;
	foreach($file as $k=>$v){
		$math = rand(1000,9999);
		$fileName = date('YmdHis',time()).$math;
		if(is_array($v) && $v){
			$recursion = uploadFile($v);
			$result[$k] = implode(',',$recursion);
		}else{
			$info = $v->validate($config)->move($path,$fileName);
			if($info){
				$result[$k] = '/upload/'.$savePath.'/'.$info->getSaveName();
			}else{
				exit(json_encode(['status'=>0,'msg'=>$v->getError()]));
			}
		}
	}
	return $result;
}

function is_mobile(){
	$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
	$mobile_browser = '0';
	if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
		$mobile_browser++;
	if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
		$mobile_browser++;
	if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
		$mobile_browser++;
	if(isset($_SERVER['HTTP_PROFILE']))
		$mobile_browser++;
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda','xda-'
	);
	if(in_array($mobile_ua, $mobile_agents))
		$mobile_browser++;
	if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
		$mobile_browser++;
	// Pre-final check to reset everything if the user is on Windows
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
		$mobile_browser=0;
	// But WP7 is also Windows, with a slightly different characteristic
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
		$mobile_browser++;
	if($mobile_browser>0)
		return true;
	else
		return false;
}