<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/15
 * Time: 10:41
 */

namespace app\wap\controller;


class About extends Common
{
	public function index()
	{
//		$About = new \app\common\About();
//		$res = $About->story();
//		$this->assign('story',$res);
		$this->assign('site_title','学院介绍');
		return $this->fetch();
	}
}