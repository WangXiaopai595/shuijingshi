<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/10
 * Time: 11:56
 */

namespace app\web\controller;


use app\common\Job;
use app\model\NewClass;
use think\Request;

class About extends Common
{
	public function __construct()
	{
		parent::__construct();
		$this->assign('reuest_url','about/index');
	}

	/**
	 * 关于我们
	 * Created by：Mp_Lxj
	 * @date 2019/1/10 16:41
	 * @return mixed
	 */
	public function index()
	{
		$About = new \app\common\About();
		$res = $About->index();
		$this->assign('about',$res);

		$this->assign('site_title','关于我们');
		return $this->fetch();
	}

	/**
	 * 文章详情
	 * Created by：Mp_Lxj
	 * @date 2019/1/10 17:13
	 * @return mixed
	 */
	public function detail()
	{
		$param = Request::instance()->param();
		if(!$param['article']){
			$this->redirect('Index/index');
		}

		$Job = new Job();
		$res = $Job->article($param['article']);
		$this->assign('article',$res);
		$this->assign('site_title',$res['article']['title']);

		return $this->fetch();
	}

	/**
	 * 新闻/活动
	 * created by:Mp_Lxj
	 * @date:2019/1/10 22:38
	 */
	public function article()
	{
		$param = Request::instance()->param();
		if(!$param['article']){
			$this->redirect('Index/index');
		}

		$Job = new Job();
		$res = $Job->article($param['article']);
		$this->assign('article',$res);
		$this->assign('site_title',$res['article']['title']);

		$newClass = new NewClass();
		$map['id'] = $res['article']['new_id'];
		$class = $newClass->getClassDetail($map);
		$this->assign('class_name',$class['name']);

		return $this->fetch();
	}

	/**
	 * 品牌故事
	 * created by:Mp_Lxj
	 * @date:2019/1/10 20:09
	 * @return mixed
	 */
	public function story()
	{
		$About = new \app\common\About();
		$res = $About->story();
		$this->assign('story',$res);
		$this->assign('site_title','品牌故事');
		return $this->fetch();
	}

	/**
	 * 公司介绍
	 * created by:Mp_Lxj
	 * @date:2019/1/10 20:47
	 * @return mixed
	 */
	public function introduce()
	{
		$About = new \app\common\About();
		$res = $About->introduce();
		$this->assign('article',$res);
		$this->assign('site_title','公司介绍');
		return $this->fetch();
	}

	/**
	 * 师资团队
	 * created by:Mp_Lxj
	 * @date:2019/1/10 21:08
	 * @return mixed
	 */
	public function teacher()
	{
		$About = new \app\common\About();
		$res = $About->teacher();
		$this->assign('teacher',$res);
		$this->assign('site_title','师资团队');
		return $this->fetch();
	}

	/**
	 * 学院环境
	 * created by:Mp_Lxj
	 * @date:2019/1/10 21:30
	 * @return mixed
	 */
	public function ambientSchool()
	{
		$About = new \app\common\About();
		$res = $About->ambient(1);
		$this->assign('about',$res);
		$this->assign('site_title','学院环境');
		return $this->fetch();
	}

	/**
	 * 宿舍环境
	 * created by:Mp_Lxj
	 * @date:2019/1/10 21:31
	 * @return mixed
	 */
	public function ambientDormitory()
	{
		$About = new \app\common\About();
		$res = $About->ambient(2);
		$this->assign('about',$res);
		$this->assign('site_title','宿舍环境');
		return $this->fetch();
	}

	/**
	 * 就业新闻
	 * created by:Mp_Lxj
	 * @date:2019/1/10 22:14
	 * @return mixed
	 */
	public function news()
	{
		$About = new \app\common\About();
		$res = $About->news(1);
		$this->assign('new',$res);
		$this->assign('site_title','学院新闻');

		$param = Request::instance()->param();
		$this->assign('param',$param);
		return $this->fetch();
	}

	/**
	 * 校园活动
	 * created by:Mp_Lxj
	 * @date:2019/1/10 22:43
	 * @return mixed
	 */
	public function activity()
	{
		$About = new \app\common\About();
		$res = $About->news(2);
		$this->assign('new',$res);
		$this->assign('site_title','校园活动');

		$param = Request::instance()->param();
		$this->assign('param',$param);
		return $this->fetch();
	}

	/**
	 * 付款方式
	 * created by:Mp_Lxj
	 * @date:2019/1/10 23:06
	 * @return mixed
	 */
	public function payment()
	{
		$About = new \app\common\About();
		$res = $About->article('paymode');
		$this->assign('about',$res);
		$this->assign('site_title','开班时间');
		return $this->fetch();
	}

	/**
	 * 来校路线
	 * created by:Mp_Lxj
	 * @date:2019/1/10 23:29
	 * @return mixed
	 */
	public function route()
	{
		$About = new \app\common\About();
		$res = $About->article('route');
		$this->assign('about',$res);
		$this->assign('site_title','来校路线');
		return $this->fetch();
	}

	/**
	 * 开班时间
	 * created by:Mp_Lxj
	 * @date:2019/1/10 23:46
	 * @return mixed
	 */
	public function classTime()
	{
		$About = new \app\common\About();
		$res = $About->article('classtime');
		$this->assign('about',$res);
		$this->assign('site_title','开班时间');
		return $this->fetch();
	}
}