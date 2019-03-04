<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/26
 * Time: 9:20
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Ambient extends Common
{
	/**
	 * 学院环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return mixed
	 */
	public function school()
	{
		$map['type'] = 1;
		$Ambient = new \app\model\Ambient();
		$img = $Ambient->ambientList($map);
		$this->assign('img',$img);
		return $this->fetch();
	}

	/**
	 * 添加学院环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addSchool()
	{
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			$Ambient = new \app\model\Ambient();
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			$param['time'] = time();
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			$param['type'] = 1;
			$id = $Ambient->ambientInsert($param);
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
	 * 修改学院环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editSchool()
	{
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		$Ambient = new \app\model\Ambient();
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			$param['type'] = 1;
			Db::startTrans();
			try{
				$Ambient->editAmbient($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$img = $Ambient->getAmbientDetail($map);
			$this->assign('img',$img);
			return $this->fetch();
		}
	}

	/**
	 * 学院环境排序
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function sortSchool()
	{
		$data = Request::instance()->param();
		$Ambient = new \app\model\Ambient();
		$groupID = explode(',',$data['id']);
		$groupSort = explode(',',$data['sort']);
		Db::startTrans();
		try{
			foreach($groupID as $k=>$v){
				$map['id'] = $v;
				$arr['sort'] = $groupSort[$k];
				$Ambient->editAmbient($map,$arr);
			}
			Db::commit();
			return trueAjax('更新成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 删除学院环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:34
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delSchool()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$Ambient = new \app\model\Ambient();
		Db::startTrans();
		try{
			$Ambient->delAmbient($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:34
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShowSchool()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Ambient = new \app\model\Ambient();
		Db::startTrans();
		try{
			$Ambient->editAmbient($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 宿舍环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return mixed
	 */
	public function dorm()
	{
		$map['type'] = 2;
		$Ambient = new \app\model\Ambient();
		$img = $Ambient->ambientList($map);
		$this->assign('img',$img);
		return $this->fetch();
	}

	/**
	 * 添加宿舍环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addDorm()
	{
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			$Ambient = new \app\model\Ambient();
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			$param['time'] = time();
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			$param['type'] = 2;
			$id = $Ambient->ambientInsert($param);
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
	 * 修改宿舍环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editDorm()
	{
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		$Ambient = new \app\model\Ambient();
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			$param['type'] = 2;
			Db::startTrans();
			try{
				$Ambient->editAmbient($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$img = $Ambient->getAmbientDetail($map);
			$this->assign('img',$img);
			return $this->fetch();
		}
	}

	/**
	 * 宿舍环境排序
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:35
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function sortDorm()
	{
		$data = Request::instance()->param();
		$Ambient = new \app\model\Ambient();
		$groupID = explode(',',$data['id']);
		$groupSort = explode(',',$data['sort']);
		Db::startTrans();
		try{
			foreach($groupID as $k=>$v){
				$map['id'] = $v;
				$arr['sort'] = $groupSort[$k];
				$Ambient->editAmbient($map,$arr);
			}
			Db::commit();
			return trueAjax('更新成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 删除宿舍环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:34
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delDorm()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$Ambient = new \app\model\Ambient();
		Db::startTrans();
		try{
			$Ambient->delAmbient($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 9:34
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShowDorm()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Ambient = new \app\model\Ambient();
		Db::startTrans();
		try{
			$Ambient->editAmbient($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}