<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/25
 * Time: 13:22
 */

namespace app\admin\controller;


use app\model\Story;
use think\Db;
use think\Request;

class Undergo extends Common
{
	/**
	 * 成长历程
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 13:32
	 * @return mixed
	 */
	public function index()
	{
		$Story = new Story();
		$story = $Story->storyList();
		$this->assign('story',$story);
		return $this->fetch();
	}

	/**
	 * 添加
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 13:32
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addStory()
	{
		$Story = new Story();
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			$param['time'] = $param['time'] ? strtotime($param['time'] . '-1-1') : time();
			$id = $Story->storyInsert($param);
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
	 * 修改
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 13:32
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editStory()
	{
		$Story = new Story();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			Db::startTrans();
			try{
				if($param['time']){
					$param['time'] = $param['time'] ? strtotime($param['time'] . '-1-1') : time();
				}else{
					unset($param['time']);
				}
				$Story->editStory($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$story = $Story->getStoryDetail($map);
			$this->assign('story',$story);
			return $this->fetch();
		}
	}

	/**
	 * 删除
	 * Created by：Mp_Lxj
	 * @date 2018/12/25 13:32
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delete()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$Story = new Story();
		Db::startTrans();
		try{
			$Story->delStory($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}