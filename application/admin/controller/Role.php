<?php
namespace app\admin\controller;
use app\admin\controller;
class Role extends Common
{
	/**
	 * 角色列表
	 * @return mixed
	 */
	public function index(){
		$field = [
			'id',//id
			'name',//角色名
			'status',//启用状态
			'sort',//排序
			'remark'//备注
		];
		$result = \think\Loader::model('Role')->roleList($field);
		$this->assign('role',$result);
		return $this->fetch();
	}

	/**
	 * 修改角色基本信息
	 * @return mixed
	 */
	public function editRole(){
		$data = \think\Request::instance()->param();
		if(\think\Request::instance()->isAjax()){
			$map['id'] = $data['id'];
			$result = \think\Loader::model('Role')->roleEdit($map,$data);
			return $result;
		}else{
			$map['id'] = $data['id'];
			$field = [
				'id',//id
				'name',//角色名
				'status',//启用状态
				'sort',//排序
				'remark'//备注
			];
			$result = \think\Loader::model('Role')->dataSingle($map,$field);
			$this->assign('role',$result);
			return $this->fetch();
		}
	}

	/**
	 * 添加角色
	 * @return mixed
	 */
	public function addRole(){
		if(\think\Request::instance()->isAjax()){
			$data = \think\Request::instance()->param();
			$result = \think\Loader::model('Role')->roleAdd($data);
			return $result;
		}else{
			return $this->fetch();
		}
	}

	/**
	 * 更新排序
	 * @return array
	 */
	public function sort(){
		$data = \think\Request::instance()->param();
		$groupID = explode(',',$data['id']);
		$groupSort = explode(',',$data['sort']);
		foreach($groupID as $k=>$v){
			$map['id'] = $v;
			$arr['sort'] = $groupSort[$k];
			\think\Loader::model('Role')->roleEdit($map,$arr);
		}
		return ['status'=>1,'msg'=>'更新成功'];
	}

	/**
	 * 修改启用状态
	 * @return mixed
	 */
	public function editStatus(){
		$data = \think\Request::instance()->param();
		$map['id'] = $data['id'];
		$result = \think\Loader::model('Role')->roleEdit($map,$data);
		return $result;
	}

	/**
	 * 删除角色
	 * @return mixed
	 */
	public function delete(){
		$data = \think\Request::instance()->param();
		$map['id'] = ['in',$data['id']];
		$result = \think\Loader::model('Role')->roleDelete($map);
		return $result;
	}

	/**
	 * 分配权限
	 * @return array|mixed
	 */
	public function roleNode(){
		$data = \think\Request::instance()->param();
		if(\think\Request::instance()->isAjax()){
			$res = true;
			\think\Db::startTrans();
			try{
				$map['role_id'] = $data['id'];
				\think\Loader::model('RoleNode')->roleDelete($map);

				$newData = [];
				foreach($data['node_id'] as $k=>$v){
					$arr = [];
					$arr['role_id'] = $data['id'];
					$arr['node_id'] = $v;
					array_push($newData,$arr);
				}
				\think\Loader::model('RoleNode')->AddAll($newData);
				\think\Db::commit();
			}catch(\Exception $e){
				$res = false;
				\think\Db::rollback();
			}

			if($res){
				$result = ['status'=>1,'msg'=>'分配成功'];
			}else{
				$result = ['status'=>0,'msg'=>'分配失败'];
			}
			return $result;
		}else{
			//查询所有节点权限
			$map['t.status'] = 1;
			$map['t1.status'] = 1;
			$field1 = ['id','title'];
			$field2 = ['title'=>'ptitle'];
			$node = \think\Loader::model('Node')->lists($map,$field1,$field2);
			$node = arrayGroup($node,'ptitle',['ptitle'],'node');
			$this->assign('node',$node);

			$this->assign('roleID',$data['id']);

			//查询当前角色有哪些权限
			unset($map);
			$map['role_id'] = $data['id'];
			$field = ['node_id'];
			$nodeID = \think\Loader::model('RoleNode')->roleList($map,$field);
			$arr = [];
			foreach($nodeID as $k=>$v){
				array_push($arr,$v['node_id']);
			}
			$this->assign('nodeID',$arr);

			return $this->fetch();
		}
	}
}