<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/24
 * Time: 16:48
 */

namespace app\admin\controller;


use app\model\VideoList;
use think\Db;
use think\Request;

class Interview extends Common
{
	/**
	 * 学员采访首页
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 17:09
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();
		$this->assign('param',$param);

		$Video = new VideoList();
		$param['type'] = 1;
		$video = $Video->videoList($param);
		$this->assign('video',$video);
		return $this->fetch();
	}

	/**
	 * 添加视频
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 17:09
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addVideo()
	{
		$Video = new VideoList();
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			$video = getCC($param['video_id']);
			if(!$video['status']){
				return falseAjax($video['msg'] ?: '获取视频信息失败');
			}
			foreach($video['data'] as $k=>$v){
				$param[$k] = $v;
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			if(!$param['is_recommend']){
				$param['is_recommend'] = 0;
			}
			$param['time'] = $param['time'] ? strtotime($param['time']) : time();
			$param['type'] = 1;
			$id = $Video->videoInsert($param);
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
	 * 修改视频
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 17:09
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editVideo()
	{
		$Video = new VideoList();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			$video = getCC($param['video_id']);
			if(!$video['status']){
				return falseAjax($video['msg'] ?: '获取视频信息失败');
			}
			foreach($video['data'] as $k=>$v){
				$param[$k] = $v;
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			if(!$param['is_recommend']){
				$param['is_recommend'] = 0;
			}
			if($param['time']){
				$param['time'] = strtotime($param['time']);
			}else{
				unset($param['time']);
			}
			$param['type'] = 1;
			Db::startTrans();
			try{
				$Video->editVideo($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$video = $Video->getVideoDetail($map);
			$this->assign('video',$video);
			return $this->fetch();
		}
	}

	/**
	 * 删除
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 17:09
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delete()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$Video = new VideoList();
		Db::startTrans();
		try{
			$Video->delVideo($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置是否推荐
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 17:08
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setRecommend()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Video = new VideoList();
		Db::startTrans();
		try{
			$Video->editVideo($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置是否显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 17:07
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShow()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Video = new VideoList();
		Db::startTrans();
		try{
			$Video->editVideo($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}