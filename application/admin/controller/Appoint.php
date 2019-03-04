<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/26
 * Time: 14:38
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Appoint extends Common
{
	/**
	 * 预约列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 14:47
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();
		$this->assign('param',$param);
		$Appoint = new \app\model\Appoint();
		$appoint = $Appoint->appointList($param);
		$this->assign('appoint',$appoint);
		return $this->fetch();
	}

	/**
	 * 预约处理
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 14:48
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function handle()
	{
		$param = Request::instance()->param();
		$Appoint = new \app\model\Appoint();

		Db::startTrans();
		try{
			$map['id'] = $param['id'];
			$Appoint->editApponit($map,$param);
			Db::commit();
			return trueAjax('处理成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}