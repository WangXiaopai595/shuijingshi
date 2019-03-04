<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/20
 * Time: 15:20
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Site extends Common
{
	/**
	 * 站点设置
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 15:21
	 * @return mixed
	 */
	public function index()
	{
		$this->returnSite();
		return $this->fetch();
	}

	/**
	 * 更新站点信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:47
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function siteUpdate()
	{
		$param = Request::instance()->param();
		$Site = new \app\model\Site();
		Db::startTrans();
		try{
			$Site->updateSite($param);
			Db::commit();
			return trueAjax('提交成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax('提交失败');
		}
	}

	/**
	 * 站点信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:07
	 */
	public function returnSite()
	{
		$Site = new \app\model\Site();
		$res = $Site->getSiteDetail();
		$res['content'] = $res['content'] ? unserialize($res['content']) : [];
		$this->assign('site',$res);
	}
}