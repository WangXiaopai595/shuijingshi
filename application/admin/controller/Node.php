<?php
namespace app\admin\controller;
use app\admin\controller;
use think\Request;

class Node extends Common
{
	/**
	 * 节点列表
	 * @return mixed
	 */
	public function index(){
		$param = Request::instance()->param();
		$this->assign('param',$param);
		$field = [
			'id',//id
			'title',//名称
			'name',//节点
			'status',//状态
			'display',//是否菜单
			'sort',//排序
		];
		$result = \think\Loader::model('Node')->nodeList($field,$param);
		$this->assign('node',$result);
		return $this->fetch();
	}

	/**
	 * 修改节点页及修改数据处理
	 * @return mixed
	 */
	public function editNode(){
		$data = \think\Request::instance()->param();
		if(\think\Request::instance()->isAjax()){
			$map['id'] = $data['id'];
			if(!isset($data['display'])){
				$data['display'] = 0;
				$data['icon'] = '';
				$data['gid'] = 0;
			}

			if(!isset($data['status'])){
				$data['status'] = 0;
			}
			$result = \think\Loader::model('Node')->nodeEdit($map,$data);
			return $result;
		}else{
			//获取图标并缓存
			$icon = \think\Db::name('icon')->field('id,icon')->cache('icon')->select();
			$this->assign('icon',$icon);

			//顶级节点列表
			$map['pid'] = 0;
			$map['status'] = 1;
			$field = ['id','title'];
			$topNode = \think\Loader::model('Node')->topList($map,$field);
			$this->assign('node',$topNode);

			//菜单列表
			unset($map);
			$map['status'] = 1;
			$field = ['id','name'];
			$menu = \think\Loader::model('NodeGroup')->menuList($map,$field);
			$this->assign('menu',$menu);

			//当前要修改的节点信息
			unset($map);
			$map['id'] = $data['id'];
			$field = [
				'id',//id
				'name',//节点
				'title',//节点名
				'status',//启动状态
				'remark',//备注
				'pid',//父级
				'level',//等级
				'sort',//排序
				'display',//是否菜单
				'gid',//菜单id
				'icon'//菜单图标
			];
			$result = \think\Loader::model('Node')->dataSingle($map,$field);
			$this->assign('data',$result);

			return $this->fetch();
		}
	}

	/**
	 * 添加节点
	 * @return mixed
	 */
	public function addNode(){
		if(\think\Request::instance()->isAjax()){
			$data = \think\Request::instance()->param();
			if(!isset($data['display'])){
				unset($data['icon']);
				unset($data['gid']);
			}
			$result = \think\Loader::model('Node')->nodeAdd($data);
			return $result;
		}else{
			//获取图标并缓存
			$icon = \think\Db::name('icon')->field('id,icon')->cache('icon')->select();
			$this->assign('icon',$icon);

			//顶级节点列表
			$map['pid'] = 0;
			$map['status'] = 1;
			$field = ['id','title'];
			$topNode = \think\Loader::model('Node')->topList($map,$field);
			$this->assign('node',$topNode);

			//菜单列表
			unset($map);
			$map['status'] = 1;
			$field = ['id','name'];
			$menu = \think\Loader::model('NodeGroup')->menuList($map,$field);
			$this->assign('menu',$menu);

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
			\think\Loader::model('Node')->nodeEdit($map,$arr);
		}
		return ['status'=>1,'msg'=>'更新成功'];
	}

	/**
	 * 更新状态
	 * @return mixed
	 */
	public function editStatus(){
		$data = \think\Request::instance()->param();
		$map['id'] = $data['id'];
		$result = \think\Loader::model('Node')->nodeEdit($map,$data);
		return $result;
	}

	/**
	 * 删除菜单
	 * @return mixed
	 */
	public function delete(){
		$data = \think\Request::instance()->param();
		$map['id'] = ['in',$data['id']];
		$result = \think\Loader::model('Node')->nodepDelete($map);
		return $result;
	}
}