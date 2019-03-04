<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/11
 * Time: 10:29
 */

namespace app\web\controller;


use app\common\Msg;
use think\Cache;
use think\Request;

class User extends Common
{
	public function __construct()
	{
		parent::__construct();
		$user = session('user');
		if(!$user){
			$this->redirect('Login/index');
		}
	}

	/**
	 * 个人中心页面
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 14:56
	 * @return mixed
	 */
	public function index()
	{
		$this->assign('site_title','个人中心');
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
		$user = session('user');
		$msg = $Msg->sendMsg($user['phone']);
		if($msg['status']){
			Cache::set('code_update_' . $user['phone'],$msg['data'],600);
			return trueAjax();
		}else{
			return $msg;
		}
	}

	/**
	 * 更新个人资料
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 15:27
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function update()
	{
		$User = new \app\common\User();
		return $User->update();
	}

	/**
	 * 安全中心
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 15:30
	 * @return mixed
	 */
	public function setting()
	{
		$this->assign('site_title','安全中心');
		return $this->fetch();
	}

	/**
	 * 设置密码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 15:40
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setPwd()
	{
		$User = new \app\common\User();
		return $User->setPwd();
	}

	/**
	 * 我的收藏
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 15:43
	 * @return mixed
	 */
	public function collection()
	{
		$User = new \app\common\User();
		$this->assign('site_title','收藏课程');
		$res =  $User->collection();
		$this->assign('collection',$res);
		return $this->fetch();
	}

	public function closeController()
	{
		$User = new \app\common\User();
		return $User->closeController();
	}
}