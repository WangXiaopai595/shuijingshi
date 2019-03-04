<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/26
 * Time: 13:20
 */

namespace app\admin\controller;


use app\model\Article;
use app\model\NewClass;
use think\Db;
use think\Request;

class News extends Common
{
	/**
	 * 新闻列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:46
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();
		$this->assign('param',$param);

		$NewClass = new NewClass();
		$map['type'] = 1;
		$class = $NewClass->classList($map);
		$this->assign('class',$class);

		$Article = new Article();
		$param['type'] = 'new';
		$article = $Article->getArticleList($param);
		$this->assign('article',$article);
		return $this->fetch();
	}

	/**
	 * 添加新闻
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:46
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addNew()
	{
		$Article = new Article();
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			if(!$param['is_recommend']){
				$param['is_recommend'] = 0;
			}
			$param['type'] = 'new';
			$param['time'] = $param['time'] ? strtotime($param['time']) : time();
			$id = $Article->articleInsert($param);
			if($id){
				return trueAjax('添加成功');
			}else{
				return falseAjax('添加失败');
			}
		}else{
			$NewClass = new NewClass();
			$map['type'] = 1;
			$class = $NewClass->classList($map);
			$this->assign('class',$class);
			return $this->fetch();
		}
	}

	/**
	 * 修改新闻
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:46
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editNew()
	{
		$Article = new Article();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			if(!$param['is_recommend']){
				$param['is_recommend'] = 0;
			}
			$param['type'] = 'new';
			if($param['time']){
				$param['time'] = strtotime($param['time']);
			}else{
				unset($param['time']);
			}
			Db::startTrans();
			try{
				$Article->editArticle($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$article = $Article->getArticleDetail($map);
			$this->assign('article',$article);

			$NewClass = new NewClass();
			$ClassMap['type'] = 1;
			$class = $NewClass->classList($ClassMap);
			$this->assign('class',$class);
			return $this->fetch();
		}
	}

	/**
	 * 删除新闻
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:45
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delNew()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$Article = new Article();
		Db::startTrans();
		try{
			$Article->delArticle($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:45
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShowNew()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Article = new Article();
		Db::startTrans();
		try{
			$Article->editArticle($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置推荐
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:45
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setRecommendNew()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Article = new Article();
		Db::startTrans();
		try{
			$Article->editArticle($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 校园活动列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:46
	 * @return mixed
	 */
	public function activity()
	{
		$param = Request::instance()->param();
		$this->assign('param',$param);

		$NewClass = new NewClass();
		$map['type'] = 2;
		$class = $NewClass->classList($map);
		$this->assign('class',$class);

		$Article = new Article();
		$param['type'] = 'activity';
		$article = $Article->getArticleList($param);
		$this->assign('article',$article);
		return $this->fetch();
	}

	/**
	 * 添加活动
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:46
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addActivity()
	{
		$Article = new Article();
		$param = Request::instance()->param();
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			if(!$param['is_recommend']){
				$param['is_recommend'] = 0;
			}
			$param['type'] = 'activity';
			$param['time'] = $param['time'] ? strtotime($param['time']) : time();
			$id = $Article->articleInsert($param);
			if($id){
				return trueAjax('添加成功');
			}else{
				return falseAjax('添加失败');
			}
		}else{
			$NewClass = new NewClass();
			$map['type'] = 2;
			$class = $NewClass->classList($map);
			$this->assign('class',$class);
			return $this->fetch();
		}
	}

	/**
	 * 修改活动
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:46
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editActivity()
	{
		$Article = new Article();
		$param = Request::instance()->param();
		$map['id'] = $param['id'];
		if(Request::instance()->isAjax()){
			$file = Request::instance()->file();
			$path = uploadFile($file);
			if($path){
				foreach($path as $k=>$v){
					$param[$k] = $v;
				}
			}
			if(!$param['is_show']){
				$param['is_show'] = 0;
			}
			if(!$param['is_recommend']){
				$param['is_recommend'] = 0;
			}
			$param['type'] = 'activity';
			if($param['time']){
				$param['time'] = strtotime($param['time']);
			}else{
				unset($param['time']);
			}
			Db::startTrans();
			try{
				$Article->editArticle($map,$param);
				Db::commit();
				return trueAjax('修改成功');
			}catch(\Exception $e){
				Db::rollback();
				return falseAjax($e->getMessage());
			}
		}else{
			$article = $Article->getArticleDetail($map);
			$this->assign('article',$article);

			$NewClass = new NewClass();
			$ClassMap['type'] = 2;
			$class = $NewClass->classList($ClassMap);
			$this->assign('class',$class);
			return $this->fetch();
		}
	}

	/**
	 * 删除活动
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:45
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delActivity()
	{
		$param = Request::instance()->param();
		$map['id'] = ['in',$param['id']];
		$Article = new Article();
		Db::startTrans();
		try{
			$Article->delArticle($map);
			Db::commit();
			return trueAjax('删除成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:45
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShowActivity()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Article = new Article();
		Db::startTrans();
		try{
			$Article->editArticle($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}

	/**
	 * 设置推荐
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:45
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setRecommendActivity()
	{
		$data = Request::instance()->param();
		$map['id'] = $data['id'];
		$Article = new Article();
		Db::startTrans();
		try{
			$Article->editArticle($map,$data);
			Db::commit();
			return trueAjax('修改成功');
		}catch(\Exception $e){
			Db::rollback();
			return falseAjax($e->getMessage());
		}
	}
}