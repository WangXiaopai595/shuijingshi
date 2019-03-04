<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/26
 * Time: 10:51
 */

namespace app\admin\controller;


use app\model\Article;
use think\Db;
use think\Request;

class Newclass extends Common
{
	/**
	 * 新闻分类列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 11:00
	 * @return mixed
	 */
	public function newsClass()
	{
		$map['type'] = 1;
		$NewClass = new \app\model\NewClass();
		$class = $NewClass->classList($map);
		$this->assign('class',$class);
		return $this->fetch();
	}

	/**
	 * 添加分类
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addNew()
	{
		$NewClass = new \app\model\NewClass();
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			$param['type'] = 1;
			$id = $NewClass->classInsert($param);
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
	 * 修改分类
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editNew()
	{
		$NewClass = new \app\model\NewClass();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			Db::startTrans();
			try{
				if(!$param['is_show']){
					$param['is_show'] = 0;
				}
				$param['type'] = 1;
				$NewClass->editClass($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$class = $NewClass->getClassDetail($map);
			$this->assign('class',$class);
			return $this->fetch();
		}
	}

	/**
	 * 删除分类
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delNew()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$workMap['new_id'] = ['in',$param['id']];
		$workMap['type'] = ['=','new'];
		$NewClass = new \app\model\NewClass();
		$Article = new Article();
		Db::startTrans();
		try{
			$NewClass->delClass($map);
			$Article->delArticle($workMap);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 新闻l类型排序
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function sortNew()
	{
		$data = Request::instance()->param();
		$NewClass = new \app\model\NewClass();
		$groupID = explode(',',$data['id']);
		$groupSort = explode(',',$data['sort']);
		Db::startTrans();
		try{
			foreach($groupID as $k=>$v){
				$map['id'] = $v;
				$arr['sort'] = $groupSort[$k];
				$NewClass->editClass($map,$arr);
			}
			Db::commit();
			return trueAjax('更新成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShowNew()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$NewClass = new \app\model\NewClass();
		Db::startTrans();
		try{
			$NewClass->editClass($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 活动分类列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 11:00
	 * @return mixed
	 */
	public function activityClass()
	{
		$map['type'] = 2;
		$NewClass = new \app\model\NewClass();
		$class = $NewClass->classList($map);
		$this->assign('class',$class);
		return $this->fetch();
	}

	/**
	 * 添加分类
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addActivity()
	{
		$NewClass = new \app\model\NewClass();
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			$param['type'] = 2;
			$id = $NewClass->classInsert($param);
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
	 * 修改分类
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editaActivity()
	{
		$NewClass = new \app\model\NewClass();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			Db::startTrans();
			try{
				if(!$param['is_show']){
					$param['is_show'] = 0;
				}
				$param['type'] = 2;
				$NewClass->editClass($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$class = $NewClass->getClassDetail($map);
			$this->assign('class',$class);
			return $this->fetch();
		}
	}

	/**
	 * 删除分类
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delActivity()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$workMap['new_id'] = ['in',$param['id']];
		$workMap['type'] = ['=','activity'];
		$NewClass = new \app\model\NewClass();
		$Article = new Article();
		Db::startTrans();
		try{
			$NewClass->delClass($map);
			$Article->delArticle($workMap);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 活动类型排序
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function sortActivity()
	{
		$data = Request::instance()->param();
		$NewClass = new \app\model\NewClass();
		$groupID = explode(',',$data['id']);
		$groupSort = explode(',',$data['sort']);
		Db::startTrans();
		try{
			foreach($groupID as $k=>$v){
				$map['id'] = $v;
				$arr['sort'] = $groupSort[$k];
				$NewClass->editClass($map,$arr);
			}
			Db::commit();
			return trueAjax('更新成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 10:59
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShowActivity()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$NewClass = new \app\model\NewClass();
		Db::startTrans();
		try{
			$NewClass->editClass($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}