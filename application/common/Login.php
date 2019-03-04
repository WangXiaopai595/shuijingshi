<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/11
 * Time: 9:51
 */

namespace app\common;


use app\model\User;
use think\Cache;
use think\Request;

class Login
{
	/**
	 * 密码登录
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 10:01
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function pwdLogin()
	{
		$param = Request::instance()->param();
		$User = new User();
		$map['phone']= $param['phone'];
		$user = $User->getUserDetail($map);
		if(!$user){
			return falseAjax('手机号错误或不存在');
		}
		if(md5($param['password']) != $user['password']){
			return falseAjax('密码错误');
		}
		return $this->loginUpdate($user);
 	}

	/**
	 * 登录信息
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 10:02
	 * @param $user
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function loginUpdate($user)
	{
		$User = new User();
		if($user['status'] == 0){
			return falseAjax('帐号已被禁用');
		}
		$user['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
		$user['last_login_time'] = time();
		$map['id'] = $user['id'];
		$User->editUser($map,$user);
		session('user',$user);
		return trueAjax('登陆成功');
	}

	/**
	 * 验证码登录
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 14:56
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function codeLogin()
	{
		$param = Request::instance()->param();
		$code = Cache::get('code_login_' . $param['phone']);

		if($param['code'] == $code){
			$User = new User();
			$map['phone']= $param['phone'];
			$user = $User->getUserDetail($map);
			if(!$user){
				return falseAjax('手机号不存在,点击确认立即注册!',1);
			}
			return $this->loginUpdate($user);
		}else{
			return falseAjax('验证码错误');
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
		$param = Request::instance()->param();
		$code = Cache::get('code_register_' . $param['phone']);

		if($param['code'] == $code){
			$User = new User();
			$map['phone']= $param['phone'];
			$user = $User->getUserDetail($map);
			if($user){
				return falseAjax('手机号已被注册');
			}
			unset($param['code']);
			$param['password'] = md5($param['password']);
			$param['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
			$param['last_login_time'] = time();
			$id = $User->userInsert($param);
			if($id){
				$param['id'] = $id;
				session('user',$param);
				return trueAjax('注册成功');
			}else{
				return falseAjax('注册失败');
			}
		}else{
			return falseAjax('验证码错误');
		}
	}

	/**
	 * 检测验证码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:55
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function checkSetPwd()
	{
		$param = Request::instance()->param();
		$code = Cache::get('code_setPwdCode_' . $param['phone']);

		if($param['code'] == $code){
			$User = new User();
			$map['phone']= $param['phone'];
			$user = $User->getUserDetail($map);
			if(!$user){
				return falseAjax('该手机号尚未注册');
			}
			return trueAjax();
		}else{
			return falseAjax('验证码错误');
		}
	}

	/**
	 * 设置密码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 17:07
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setPasswordPost()
	{
		$param = Request::instance()->param();
		$User = new User();
		$map['phone']= $param['phone'];
		$user = $User->getUserDetail($map);
		if(!$user){
			return falseAjax('该手机号尚未注册');
		}

		$user['password'] = md5($param['password']);
		$User->editUser($map,$user);
		return trueAjax('设置成功');
	}
}