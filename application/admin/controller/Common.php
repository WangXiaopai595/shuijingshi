<?php
namespace app\admin\controller;
use think\Controller;
class Common extends Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!\think\Session::has('member')){
			$this->redirect('login/index');
			exit;
		}else{
			$user = \think\Session::get('member');
			$this->assign('member',$user);
		}

		$user = \think\Session::get('member');
		//当前用户状态是否正常
		$cacheStatus = \think\Cache::get('status');
		if(!isset($cacheStatus[$user['id']])){
			$map['id'] = $user['id'];
			$adminStatus = \think\Loader::model('Admin')->dataSingle($map,'status');
			$status = $adminStatus['status'];
			$cacheStatus[$user['id']] = $status;
			\think\Cache::set('status',$cacheStatus);
		}else{
			$status = $cacheStatus[$user['id']];
		}
		//若账号被禁用，则返回错误码
		if(!$status){
			\think\Session::delete('member');
			$this->error('账号已被禁用','login/index');
			exit;
		}

		//将用户可操作的权限返回前端
		$node = $this->__node();
		$this->assign('operable',$node);

		$controller = \think\Request::instance()->controller();
		$action = \think\Request::instance()->action();
		$url = strtolower($controller.'/'.$action);
		if(!in_array($url,$node)){
			$this->error('没有权限操作此项','index/main');
			exit;
		}
	}

	/**
	 * 获取当前用户可操作的权限
	 * @return mixed
	 */
	public function __node(){
		$user = \think\Session::get('member');
		$cacheNode = \think\Cache::get('node');

		if(!isset($cacheNode[$user['id']])){
			//当前用户可操作的组
			$map['t.admin_id'] = $user['id'];
			$map['t1.status'] = 1;
			$roleID = \think\Loader::model('RoleAdmin')->dataFind($map,['t.role_id']);

			//当前用户可操作的节点id
			unset($map);
			$map['role_id'] = $roleID['role_id'];
			$field = ['node_id'];
			$nodeID = \think\Loader::model('RoleNode')->roleList($map,$field);
			$arr = [];
			foreach($nodeID as $k=>$v){
				array_push($arr,$v['node_id']);
			}

			//当前用户可访问的菜单
			unset($map);
			$map['t.status'] = ['eq',1];
			$map['t.id'] = ['in',$arr];
			$field = [
				'name',
			];
			$node = \think\Loader::model('Node')->adminNode($map,$field);
			$res = [];
			foreach($node as $k=>$v){
				$url = strtolower($v['pname'].'/'.$v['name']);
				array_push($res,$url);
			}
			$cacheNode[$user['id']] = $res;
			\think\Cache::set('node',$cacheNode);
		}else{
			$res = $cacheNode[$user['id']];
		}
		return $res;
	}
}