<?php
namespace app\admin\controller;
use app\admin\controller;
class Group extends Common
{
	/**
	 * 菜单列表页
	 * @return mixed
	 */
	public function index(){
		$field = [
			'id',//id
			'status',//状态值,
			'name',//名称
			'sort'//排序
		];
		$result = \think\Loader::model('NodeGroup')->groupList($field);
		$this->assign('group',$result);
		return $this->fetch();
	}

	/**
	 * 添加菜单
	 * @return mixed
	 */
	public function addGroup(){
		if(\think\Request::instance()->isAjax()){
			$data = \think\Request::instance()->param();
			$result = \think\Loader::model('NodeGroup')->groupAdd($data);
			return $result;
		}else{
			//获取图标并缓存
			$icon = \think\Db::name('icon')->field('id,icon')->cache('icon')->select();
			$this->assign('icon',$icon);
			return $this->fetch();
		}
	}

	/**
	 * 修改菜单
	 * @return mixed
	 */
	public function editGroup(){
		$data = \think\Request::instance()->param();
		if(\think\Request::instance()->isAjax()){
			$map['id'] = $data['id'];
			$data['status'] = isset($data['status']) ? $data['status'] : 0;
			$result = \think\Loader::model('NodeGroup')->groupEdit($map,$data);
			return $result;
		}else{
			//获取图标并缓存
			$icon = \think\Db::name('icon')->field('id,icon')->cache('icon')->select();
			$this->assign('icon',$icon);
			$map['id'] = $data['id'];
			$field = [
				'id',//id
				'status',//状态值,
				'icon',//图标
				'name',//名称
				'sort'//排序
			];
			$result = \think\Loader::model('NodeGroup')->dataSingle($map,$field);
			$this->assign('group',$result);
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
			\think\Loader::model('NodeGroup')->groupEdit($map,$arr);
		}
		return ['status'=>1,'msg'=>'更新成功'];
	}

	/**
	 * 修改菜单启用状态
	 * @return mixed
	 */
	public function editStatus(){
		$data = \think\Request::instance()->param();
		$map['id'] = $data['id'];
		$result = \think\Loader::model('NodeGroup')->groupEdit($map,$data);
		return $result;
	}

	/**
	 * 删除菜单
	 * @return mixed
	 */
	public function delete(){
		$data = \think\Request::instance()->param();
		$map['id'] = ['in',$data['id']];
		$result = \think\Loader::model('NodeGroup')->groupDelete($map);
		return $result;
	}
}