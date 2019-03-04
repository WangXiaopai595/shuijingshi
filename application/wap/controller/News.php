<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/15
 * Time: 8:44
 */

namespace app\wap\controller;


use app\model\Article;
use think\Request;

class News extends Common
{
	/**
	 * 新闻列表
	 * Created by：Mp_Lxj
	 * @date 2019/1/15 9:19
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function index()
	{
		$About = new \app\common\About();
		$res = $About->news(1);

		if(Request::instance()->isAjax()){
			$data = $res['article']['list']->toArray();
			foreach($data['data'] as &$v){
				$v['time'] = date('Y-m-d H:i:s',$v['time']);
			}
			return trueAjax('',$data);
		}else{
			$this->assign('new',$res);
			$this->assign('site_title','学院新闻');

			$param = Request::instance()->param();
			$this->assign('param',$param);
			return $this->fetch();
		}
	}

	/**
	 * 文章详情
	 * Created by：Mp_Lxj
	 * @date 2019/1/15 9:19
	 * @return mixed
	 */
	public function detail()
	{
		$param = Request::instance()->param();
		if(!$param['article']){
			$this->redirect('Index/index');
		}

		$Article = new Article();
		$map['id'] = $param['article'];
		$map['is_show'] = $Article::IS_SHOW_TRUE;
		$res = $Article->getArticleDetail($map);
		$Article->peopleInc($map);
		$this->assign('article',$res);
		$this->assign('site_title',$res['title']);

		$this->assign('is_project',true);

		return $this->fetch();
	}

	/**
	 * 助学贷款页面
	 * Created by：Mp_Lxj
	 * @date 2019/1/15 10:35
	 * @return mixed
	 */
	public function loan()
	{
		$this->assign('site_title','助学贷款');
		return $this->fetch();
	}
}