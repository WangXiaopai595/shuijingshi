<?php

/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/7
 * Time: 16:41
 */
namespace app\wap\controller;
use app\model\AboutClass;
use app\model\CourseClass;
use app\model\Menu;
use app\model\Site;
use app\model\User;
use think\Controller;

class Common extends Controller
{
	public function __construct()
	{
		parent::__construct();
		//用户信息
		$user = session('user');
		if($user){
			$User = new User();
			$map['id'] = $user['id'];
			$user = $User->getUserDetail($map);
			if($user['status'] == 0){
				$user = [];
			}
			session('user',$user);
 		}
		$this->assign('user',$user ?: []);

		//站点信息
		$Site = new Site();
		$site = $Site->getSiteDetail();
		$site = $site['content'] ? unserialize($site['content']) : [];
		$this->assign('site',$site);

		if(!is_mobile()){
			$this->redirect('/web/Index/index');
		}
	}

	/**
	 * 获取菜单
	 * Created by：Mp_Lxj
	 * @date 2019/1/7 16:58
	 * @return mixed
	 */
	public function _menu()
	{
		//主菜单
		$Menu = new Menu();
		$menu_map['is_show'] = $Menu::IS_SHOW_TRUE;
		$menu = $Menu->menuList($menu_map);

		//课程二级菜单
		$Course = new CourseClass();
		$course_map['is_show'] = $Course::IS_SHOW_TRUE;
		$course = $Course->classList($course_map,['id','name','icon']);

		//关于我们二级菜单
		$AboutClass = new AboutClass();
		$about_map['parent_id'] = 0;
		$about_map['is_show'] = $AboutClass::IS_SHOW_TRUE;
		$class = $AboutClass->classList($about_map);
		unset($map);
		foreach($class as & $v){
			$about_class_map['parent_id'] = $v['id'];
			$about_class_map['is_show'] = $AboutClass::IS_SHOW_TRUE;
			$v['child'] = $AboutClass->classList($about_class_map);
		}

		foreach($menu as &$v){
			//type:0-仅有1级菜单  1课程  2关于我们
			if($v['id'] == 1){
				$v['type'] = 1;
				$v['child'] = $course;
			}elseif($v['id'] == 5){
				$v['type'] = 2;
				$v['child'] = $class;
			}else{
				$v['type'] = 0;
			}
		}
		return $menu;
	}
}