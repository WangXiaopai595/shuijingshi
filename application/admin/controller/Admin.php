<?php
namespace app\admin\controller;
use app\admin\controller;
class Admin extends Common
{
	/**
	 * 后台管理员列表
	 * @return mixed
	 */
	public function index(){
		$field = [
			'id',//用户id
			'user',//用户名
			'status',//账号状态
			'last_login_time',//最后登录时间
			'last_login_ip',//最后登录ip
			'realname',//管理员身份
			'phone',//管理员手机号码
		];
		$result = \think\Loader::model('Admin')->userList($field);
		$this->assign('user',$result);
		return $this->fetch();
	}

	/**
	 * 注册管理员账号
	 * @return mixed
	 */
	public function addAdmin(){
		if(\think\Request::instance()->isAjax()){
			$data = \think\Request::instance()->param();
			$role['role_id'] = $data['role'];
			unset($data['role']);
			$data['password'] = md5($data['password']);
			$role['admin_id'] = \think\Loader::model('Admin')->adminAdd($data);
			if($role['admin_id']){
				$res = \think\Loader::model('RoleAdmin')->adminRoleAdd($role);
			}else{
				$res = false;
			}
			if($res){
				$result = ['status'=>1,'msg'=>'添加成功'];
			}else{
				$result = ['status'=>0,'msg'=>'参数错误'];
			}
			return $result;
		}else{
			$map['status'] = 1;
			$field = ['id','name'];
			$role = \think\Loader::model('Role')->lists($map,$field);
			$this->assign('role',$role);
			return $this->fetch();
		}
	}

	/**
	 * 修改用户状态
	 * @return mixed
	 */
	public function editStatus(){
		$data = \think\Request::instance()->param();
		$map['id'] = $data['id'];

		//更新用户状态缓存
		$cacheStatus = \think\Cache::get('status');
		unset($cacheStatus[$data['id']]);
		\think\Cache::set('status',$cacheStatus);

		$result = \think\Loader::model('Admin')->userEdit($map,$data);
		return $result;
	}

	/**
	 * 删除账号
	 * @return mixed
	 */
	public function delete(){
		$data = \think\Request::instance()->param();
		$map['id'] = ['in',$data['id']];
		$result = \think\Loader::model('Admin')->userDelete($map);
		return $result;
	}

	/**
	 * 修改用户信息
	 * @return mixed
	 */
	public function edit(){
		$data = \think\Request::instance()->param();
		if(\think\Request::instance()->isAjax()){
			//用户表操作
			$map['id'] = $data['id'];
			if($data['password']){
				$data['password'] = md5($data['password']);
			}else{
				unset($data['password']);
			}
			$data['status'] = isset($data['status']) ? $data['status'] : 0;

			//角色用户表操作条件
			$roleMap['admin_id'] = $data['id'];
			$roleID['role_id'] = $data['role'];
			unset($data['role']);

			\think\Db::startTrans();
			try{
				$res = true;
				\think\Loader::model('Admin')->userEdit($map,$data);
				\think\Loader::model('RoleAdmin')->adminRoleEdit($roleMap,$roleID);
				\think\Db::commit();
			}catch(\Exception $e){
				$res = false;
				\think\Db::rollback();
			}
			if($res){
				$result = ['status'=>1,'msg'=>'修改成功'];
			}else{
				$result = ['status'=>0,'msg'=>'参数错误'];
			}
			return $result;
		}else{
			//当前启用状态的角色
			$map['status'] = 1;
			$field = ['id','name'];
			$role = \think\Loader::model('Role')->lists($map,$field);
			$this->assign('role',$role);

			//当前用户所属角色
			unset($map);
			$map['admin_id'] = $data['id'];
			$field = ['role_id'];
			$roleID = \think\Loader::model('RoleAdmin')->dataSingle($map,$field);
			$this->assign('roleID',$roleID);

			//当前修改用户的基本信息
			unset($map);
			$map['id'] = $data['id'];
			$field = [
				'id',//用户id
				'user',//用户名
				'status',//账号状态
				'remark',//备注
				'realname',//管理员身份
				'phone',//管理员手机号码
				'email'//邮箱
			];
			$result = \think\Loader::model('Admin')->dataSingle($map,$field);
			$this->assign('user',$result);
			return $this->fetch();
		}
	}

}