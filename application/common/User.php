<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/11
 * Time: 15:21
 */

namespace app\common;


use app\model\ClassCollection;
use app\model\ClassType;
use think\Cache;
use think\Request;

class User
{
	/**
	 * 修改个人资料
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 15:26
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function update()
	{
		$user = session('user');
		$param = Request::instance()->param();
		$code = Cache::get('code_update_' . $user['phone']);

		if($param['code'] != $code){
			return falseAjax('验证码错误');
		}

		$file = Request::instance()->file();
		$path = uploadFile($file);
		if($path){
			foreach($path as $k=>$v){
				$user[$k] = $v;
			}
		}

		if($param['phone']){
			$user['phone'] = $param['phone'];
		}
		if($param['name']){
			$user['name'] = $param['name'];
		}

		$map['id'] = $user['id'];
		$User = new \app\model\User();
		$User->editUser($map,$user);
		session('user',$user);
		return trueAjax('修改资料成功');
	}

	/**
	 * 修改密码
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 15:40
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setPwd()
	{
		$user = session('user');
		$param = Request::instance()->param();

		if(md5($param['pwd']) != $user['password']){
			return falseAjax('原密码错误');
		}

		$user['password'] = md5($param['password']);
		$map['id'] = $user['id'];

		$User = new \app\model\User();
		$User->editUser($map,$user);
		return trueAjax('修改成功');
	}

	/**
	 * 收藏列表
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 15:50
	 * @return mixed
	 */
	public function collection($is_mobile = false)
	{
		if($is_mobile){
			$classType = new ClassType();
			$class_map['is_show'] = $classType::IS_SHOW_TRUE;
			$class_map['level'] = 1;
			$res['type'] = $classType->getClassList($class_map);

			$param = Request::instance()->param();
			if($param['type_id']){
				$map['type_id'] = $param['type_id'];
			}
		}

		$user = session('user');
		$collection = new ClassCollection();
		$map['t.uid'] = $user['id'];
		$map['t1.is_show'] = 1;
		$res['class'] = $collection->getCollectionList($map);
		$res['count'] = $collection->getCollectionCount($map);
		return $res;
	}

	/**
	 * 取消收藏
	 * Created by：Mp_Lxj
	 * @date 2019/1/11 16:03
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function closeController()
	{
		$user = session('user');
		$param = Request::instance()->param();
		$map['uid'] = $user['id'];
		$map['class_id'] = $param['class_id'];
		$collection = new ClassCollection();
		$collection->delCollect($map);
		return trueAjax('取消成功');
	}
}