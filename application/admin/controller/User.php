<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/20
 * Time: 16:54
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class User extends Common
{
	/**
	 * 用户列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @return mixed
	 */
	public function index()
	{
		$User = new \app\model\User();
		$param = Request::instance()->param();
		$user = $User->userList($param);
		$this->assign('user',$user);
		$this->assign('param',$param);
		return $this->fetch();
	}

	/**
	 * 修改用户状态
	 * created by:Mp_Lxj
	 * @date:2018/12/21 19:07
	 * @return array|\Illuminate\Http\JsonResponse
	 */
	public function userStatus()
	{
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		$user = new \app\model\User();
		Db::startTrans();
		try{
			$user->editUser($map,$param);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 修改用户信息
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @return array|\Illuminate\Http\JsonResponse|mixed
	 */
	public function userDetail()
	{
		$User = new \app\model\User();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			$count = $User->getUserCount($param['phone']);
			if($count){
				return falseAjax('该手机号已被注册!');
			}
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			if($param['password']){
				$param['password'] = md5($param['password']);
			}else{
				unset($param['password']);
			}
			$param['time'] = time();
			if(!$param['status']){
				$param['status'] = 0;
			}
			$User->editUser($map,$param);
			return trueAjax('修改成功');
		}else{
			$user = $User->getUserDetail($map);
			$this->assign('user',$user);
			return $this->fetch();
		}
	}

	/**
	 * 添加用户
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:06
	 * @return array|mixed|void
	 */
	public function addUser()
	{
		$User = new \app\model\User();
		if(Request::instance()->isAjax()){
			$param = Request::instance()->param();
			$count = $User->getUserCount($param['phone']);
			if($count){
				return falseAjax('该手机号已被注册!');
			}
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			$param['password'] = md5($param['password']);
			$param['time'] = time();
			if(!$param['status']){
				$param['status'] = 0;
			}
			$id = $User->userInsert($param);
			if($id){
				return trueAjax('添加成功');
			}else{
				return falseAjax('添加失败');
			}
		}else{
			return $this->fetch();
		}
	}

	/**
	 * 删除用户
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:49
	 * @return array|\Illuminate\Http\JsonResponse
	 */
	public function delete()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$user = new \app\model\User();
		Db::startTrans();
		try{
			$user->delUser($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}