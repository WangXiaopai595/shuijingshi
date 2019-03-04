<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/9
 * Time: 16:25
 */

namespace app\wap\controller;


use think\Request;

class Work extends Common
{
	public function __construct()
	{
		parent::__construct();
		$this->assign('reuest_url','work/index');
	}

	/**
	 * 学员作品页
	 * Created by：Mp_Lxj
	 * @date 2019/1/9 16:31
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();

		$Work = new \app\common\Work();
		$res = $Work->index();

		if(Request::instance()->isAjax()){
			return trueAjax('',$res['work']['list']);
		}else{
			$this->assign('site_title','学员作品');
			$this->assign('work',$res);
			$this->assign('param',$param);
			return $this->fetch();
		}
	}
}