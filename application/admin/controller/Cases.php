<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/25
 * Time: 14:18
 */

namespace app\admin\controller;


use app\model\VideoList;
use think\Db;
use think\Request;

class Cases extends Common
{
	/**
	 * 案例列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 14:30
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();
		$this->assign('param',$param);
		$Case = new \app\model\Cases();
		$case = $Case->caseList($param);
		$this->assign('case',$case);
		return $this->fetch();
	}

	/**
	 * 请求数据处理
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 15:22
	 * @param $data
	 * @return array
	 */
	public function dataHandle($data)
	{
		$array = [];

		for($i = 1; $i <= 3; $i++){
			if($data['video_id_' . $i] && $data['icon_' . $i]){
				$arr = [];
				$video = getCC($data['video_id_' . $i]);
				foreach($video['data'] as $k=>$v){
					$arr[$k] = $v;
				}
				$arr['video_id'] = $data['video_id_' . $i];
				if($data['icon_' . $i]){
					$arr['icon'] = $data['icon_' . $i];
				}
				$arr['title'] = $data['title_' . $i];
				if($arr){
					$array[] = $arr;
				}
			}else if($data['video_id_' . $i] && !$data['icon_' . $i]){
				exit(json_encode(['status'=>0,'msg'=>'请上传封面图']));
			}
			unset($data['video_id_' . $i],$data['icon_' . $i],$data['title_' . $i]);
		}

		return ['data' => $data,'video' => $array];
	}

	public function handleData($data)
	{
		$array = [];

		for($i = 1; $i <= 3; $i++){
			if($data['video_id_' . $i]){
				$arr = [];
				$video = getCC($data['video_id_' . $i]);
				foreach($video['data'] as $k=>$v){
					$arr[$k] = $v;
				}
				$arr['video_id'] = $data['video_id_' . $i];
				if($data['icon_' . $i]){
					$arr['icon'] = $data['icon_' . $i];
				}
				$arr['title'] = $data['title_' . $i];
				$arr['vid'] = $data['vid_' . $i];
				if($arr){
					$array[] = $arr;
				}
			}
			unset($data['video_id_' . $i],$data['icon_' . $i],$data['title_' . $i],$data['vid_' . $i]);
		}

		return ['data' => $data,'video' => $array];
	}

	/**
	 * 添加案例
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 15:26
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addCase()
	{
		$Case = new \app\model\Cases();
		$param = Request::instance()->param();
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
			$data = $this->dataHandle($param);
			Db::startTrans();
			try{
				$VideoList = new VideoList();
				$id = $Case->caseInsert($data['data']);
				foreach($data['video'] as $v){
					$v['case_id'] = $id;
					$v['type'] = 3;
					$VideoList->videoInsert($v);
				}
				Db::commit();
				return trueAjax('添加成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			return $this->fetch();
		}
	}

	/**
	 * 修改
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 15:32
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editCase()
	{
		$Case = new \app\model\Cases();
		$param = Request::instance()->param();
		$VideoList = new VideoList();
		$map['id'] = $param['id'];
		$videoMap['case_id'] = $param['id'];
		if(Request::instance()->isAjax()){
			if(!$param['id']){
				return falseAjax();
			}
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
			$data = $this->handleData($param);
			Db::startTrans();
			try{
				$Case->editCase($map,$data['data']);
				foreach($data['video'] as &$v){
					$vid = $v['vid'];
					unset($v['vid']);
					$v['type'] = 3;
					if($vid){
						$video_map['id'] = $vid;
						$VideoList->editVideo($video_map,$v);
					}else{
						$v['case_id'] = $param['id'];
						$VideoList->videoInsert($v);
					}
				}
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$case = $Case->getCaseDetail($map);
			$this->assign('case',$case);
			$video = $VideoList->getVideoList($videoMap);
			$this->assign('video',$video);
			return $this->fetch();
		}
	}

	/**
	 * 排序
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 15:34
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function sort()
	{
		$data = Request::instance()->param();
		$Case = new \app\model\Cases();
		$groupID = explode(',',$data['id']);
		$groupSort = explode(',',$data['sort']);
		Db::startTrans();
		try{
			foreach($groupID as $k=>$v){
				$map['id'] = $v;
				$arr['sort'] = $groupSort[$k];
				$Case->editCase($map,$arr);
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
	 * @date 2018/12/25 15:33
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delete()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$Case = new \app\model\Cases();
		$VideoList = new VideoList();
		$videoMap['case_id'] = ['in',$param['id']];
		Db::startTrans();
		try{
			$Case->delCase($map);
			$VideoList->delVideo($videoMap);
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
	 * @date 2018/12/25 15:57
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShow()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Case = new \app\model\Cases();
		Db::startTrans();
		try{
			$Case->editCase($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}