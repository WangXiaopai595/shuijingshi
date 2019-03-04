<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/8
 * Time: 16:09
 */

namespace app\web\controller;


use app\common\Index;
use app\common\Msg;
use think\Request;

class Course extends Common
{
	public function __construct()
	{
		parent::__construct();
		$this->assign('reuest_url','course/index');
	}

	/**
	 * 精品课程
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 16:12
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();
		if(!$param['course']){
			$this->redirect('Course/index',['course' => 1]);
		}
		$common = new Index();
		$res = $common->course(false);

		$this->assign('site_title',$res['course']['name']);
		$this->assign('course',$res);
		return $this->fetch();
	}

	/**
	 * 在线预约
	 * created by:Mp_Lxj
	 * @date:2019/1/8 20:29
	 * @return array|\Illuminate\Http\JsonResponse
	 */
	public function appoint()
	{
		$common = new Index();
		return $common->appoint();
	}

	public function sendMsg()
	{
		$Msg = new Msg();
		$Msg->sendMsg('18302370061');
	}
}