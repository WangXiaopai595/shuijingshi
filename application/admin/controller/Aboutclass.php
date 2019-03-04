<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/25
 * Time: 10:24
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Aboutclass extends Common
{
	/**
	 * 菜单列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 10:38
	 * @return mixed
	 */
	public function index()
	{
		$AboutClass = new \app\model\AboutClass();
		$map['parent_id'] = 0;
		$class = $AboutClass->classList($map);
		unset($map);
		foreach($class as & $v){
			$map['parent_id'] = $v['id'];
			$v['child'] = $AboutClass->classList($map);
		}
		$this->assign('class',$class);
		return $this->fetch();
	}

	/**
	 * 添加
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 10:38
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addClass()
	{
		$AboutClass = new \app\model\AboutClass();
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			$id = $AboutClass->classInsert($param);
			if($id){
				return trueAjax('添加成功');
			}else{
				return falseAjax('添加失败');
			}
		}else{
			$map['level'] = 1;
			$map['parent_id'] = 0;
			$list = $AboutClass->classList($map);
			$this->assign('list',$list);
			return $this->fetch();
		}
	}

	/**
	 * 修改
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 10:38
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editClass()
	{
		$AboutClass = new \app\model\AboutClass();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			Db::startTrans();
			try{
				if(!$param['is_show']){
					$param['is_show'] = 0;
				}
				$AboutClass->editClass($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$class = $AboutClass->getClassDetail($map);
			$this->assign('class',$class);
			unset($map);
			$map['level'] = ['=',1];
			$map['parent_id'] = ['=',0];
			$map['id'] = ['<>',$param['id']];
			$list = $AboutClass->classList($map);
			$this->assign('list',$list);
			return $this->fetch();
		}
	}

	/**
	 * 排序
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 10:38
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function sort()
	{
		$data = Request::instance()->param();
		$AboutClass = new \app\model\AboutClass();
		$groupID = explode(',',$data['id']);
		$groupSort = explode(',',$data['sort']);
		Db::startTrans();
		try{
			foreach($groupID as $k=>$v){
				$map['id'] = $v;
				$arr['sort'] = $groupSort[$k];
				$AboutClass->editClass($map,$arr);
			}
			Db::commit();
			return trueAjax('更新成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 删除
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 10:37
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delete()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$workMap['class_id'] = ['in',$param['id']];
		$AboutClass = new \app\model\AboutClass();
		Db::startTrans();
		try{
			$AboutClass->delClass($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置是否显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 10:37
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShow()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$AboutClass = new \app\model\AboutClass();
		Db::startTrans();
		try{
			$AboutClass->editClass($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}