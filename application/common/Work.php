<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/9
 * Time: 16:27
 */

namespace app\common;


use app\model\Banner;
use app\model\WorkClass;
use think\Request;

class Work
{
	public function index()
	{
		$WorkClass = new WorkClass();
		$param = Request::instance()->param();

		//分类
		$class_map['is_show'] = $WorkClass::IS_SHOW_TRUE;
		$res['class'] = $WorkClass->classList($class_map);

		//作品
		$Work = new \app\model\Work();
		$work_map['t.class_id'] = $param['class_id'] ?: $res['class'][0]['id'];
		$work_map['t.is_show'] = $Work::IS_SHOW_TRUE;
		$work_map['t1.is_show'] = $WorkClass::IS_SHOW_TRUE;
		$res['work'] = $Work->getWorkList($work_map,$param);

		$res['count'] = $Work->getWorkCount($work_map);

		//banner图
		$Banner = new Banner();
		$banner_map['mode'] = '学员作品';
		$res['banner'] = $Banner->getBannerFirst($banner_map);

		return $res;
	}
}