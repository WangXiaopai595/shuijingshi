<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/8
 * Time: 13:40
 */

namespace app\common;


use think\Cache;
use think\Config;

class Msg
{
	/**
	 * 获取token
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 13:42
	 * @return mixed
	 */
	public function getToken()
	{
		$token = Cache::get('msg_token');
		if(!$token){
			$config = Config::get('msg');
			$url = 'https://dev.zbj.com/oauth2/accesstoken';
			$data = [
				'client_id' => $config['appKey'],
				'grant_type' => 'client_credentials'
			];
			$data['client_secret'] = $this->sign($data);
			$url .= '?' . urldecode(http_build_query($data));
			$res = $this->sendcurl($url,'',['Content-type: application/x-www-form-urlencoded']);
			$res = json_decode($res,true);
			$token = $res['access_token'];
			Cache::set('msg_token',$token,86400 * 3);
		}
		return $token;
	}

	/**
	 * 发送短信
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 13:58
	 * @param $mobile
	 * @param $templateCode
	 * @return mixed
	 */
	public function sendMsg($mobile)
	{
		$msg = rand(100000,999999);
		$userList[] = [
			'phone' => $mobile . '',
			'param' => [
				'code'=>$msg . ''
			]
		];
		$config = Config::get('msg');
		$url = 'https://dev.zbj.com/gateway';
		$data = [
			'appKey' => $config['appKey'],
			'accessToken' => $this->getToken(),
			'method' => 'zbj.sms.sendTemplateSMS',
			'v' => '1.0',
			'format' => 'json',
			'locale' => 'zh_CN',
			'timestamp' => time() . '000',
			'templateCode' => $config['tmp'],
			'signId' => $config['signId'],
			'batchId' => $this->getBatchId(),
			'userList' => json_encode($userList)
		];
		$data['sign'] = $this->sign($data);
		$url .= '?' . urldecode(http_build_query($data));
		$res = $this->sendcurl($url,[],['Content-type: application/x-www-form-urlencoded']);
		$res = json_decode($res,true);
		if($res['successToken'] === '@@$-SUCCESS_TOKEN$-@@'){
			return trueAjax('',$msg);
		}else{
			return falseAjax('发送失败');
		}
	}

	/**
	 * 获取全局唯一id值
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 13:55
	 * @return string
	 */
	public function getBatchId()
	{
		mt_srand((double)microtime()*10000);
		$charid = strtoupper(md5(uniqid(rand(), true)));
		return $charid;
	}

	/**
	 * 签名
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 13:42
	 * @param $data
	 * @return string
	 */
	public function sign($data)
	{
		ksort($data);
		$config = Config::get('msg');
		$str = $config['secretKey'];
		foreach($data as $k=>$v){
			$str .= $k . $v;
		}
		return strtoupper(sha1($str . $config['secretKey']));
	}

	/**
	 * 发送请求
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 13:59
	 * @param $url
	 * @param array $data
	 * @param array $header
	 * @param string $method
	 * @param bool $ssl
	 * @return mixed
	 */
	private function sendcurl($url,$data=[],$header=[],$method='POST',$ssl=true){
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
}