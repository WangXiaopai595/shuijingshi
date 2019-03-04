<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/11
 * Time: 8:45
 */

namespace app\web\controller;


use app\common\Msg;
use think\Cache;
use think\Controller;
use think\Request;

class Login extends Common
{
	public function __construct()
	{
		parent::__construct();
		$user = session('user');
		if($user){
			$this->redirect('Index/index');
		}
	}
	/**
	 * 登录页面
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 10:27
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();
		if($param['top_url']){
			session('top_url',$param['top_url']);
		}
		$url = session('top_url');
		$this->assign('top_url',$url);
		$this->assign('site_title','用户登录');
		return $this->fetch();
	}

	/**
	 * 发送短信
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 10:27
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function getMsg()
	{
		$Msg = new Msg();
		$param = Request::instance()->param();
		$msg = $Msg->sendMsg($param['phone']);
		if($msg['status']){
			Cache::set('code_login_' . $param['phone'],$msg['data'],600);
			return trueAjax();
		}else{
			return $msg;
		}
	}

	/**
	 * 密码登录
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 10:02
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function pwdLogin()
	{
		$Login = new \app\common\Login();
		return $Login->pwdLogin();
	}

	/**
	 * 验证码登录
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 14:53
	 */
	public function codeLogin()
	{
		$Login = new \app\common\Login();
		return $Login->codeLogin();
	}

	/**
	 * 用户注册
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:17
	 * @return mixed
	 */
	public function register()
	{
		$this->assign('site_title','用户注册');
		return $this->fetch();
	}

	/**
	 * 注册验证码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:16
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function registerMsg()
	{
		$Msg = new Msg();
		$param = Request::instance()->param();
		$msg = $Msg->sendMsg($param['phone']);
		if($msg['status']){
			Cache::set('code_register_' . $param['phone'],$msg['data'],600);
			return trueAjax();
		}else{
			return $msg;
		}
	}

	/**
	 * 用户注册
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:39
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function registerPost()
	{
		$Login = new \app\common\Login();
		return $Login->registerPost();
	}

	/**
	 * 找回密码验证码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:16
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function setPwdCode()
	{
		$Msg = new Msg();
		$param = Request::instance()->param();
		$msg = $Msg->sendMsg($param['phone']);
		if($msg['status']){
			Cache::set('code_setPwdCode_' . $param['phone'],$msg['data'],600);
			return trueAjax();
		}else{
			return $msg;
		}
	}

	/**
	 * 找回密码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:17
	 * @return mixed
	 */
	public function setPwd()
	{
		$this->assign('site_title','找回密码');
		return $this->fetch();
	}

	/**
	 * 检测验证码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:56
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function checkSetPwd()
	{
		$Login = new \app\common\Login();
		return $Login->checkSetPwd();
	}

	/**
	 * 设置密码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 17:00
	 * @return mixed
	 */
	public function setPassword()
	{
		$param = Request::instance()->param();
		if(!$param['phone']){
			$this->redirect('Index/index');
		}
		$this->assign('param',$param);
		$this->assign('site_title','找回密码');
		return $this->fetch();
	}

	/**
	 * 设置密码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 17:07
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setPasswordPost()
	{
		$Login = new \app\common\Login();
		return $Login->setPasswordPost();
	}
}