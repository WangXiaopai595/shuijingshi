<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/10
 * Time: 14:00
 */

namespace app\admin\controller;


use app\model\Article;
use think\Db;
use think\Request;

class Media extends Common
{
	/**
	 * 文章列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 14:28
	 * @return mixed
	 */
	public function index()
	{
		$param = Request::instance()->param();
		$this->assign('param',$param);

		$Article = new Article();
		$param['type'] = 'media';
		$article = $Article->articleList($param);
		$this->assign('article',$article);
		return $this->fetch();
	}

	/**
	 * 添加文章
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 14:28
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function addArticle()
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
			$param['type'] = 'media';
			$param['time'] = $param['time'] ? strtotime($param['time']) : time();
			$id = $Article->articleInsert($param);
			if($id){
				return trueAjax('添加成功');
			}else{
				return falseAjax('添加失败');
			}
		}else{
			return $this->fetch();
		}
	}

	/**
	 * 修改文章
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 14:28
	 * @return array|\Illuminate\Http\JsonResponse|mixed|void
	 */
	public function editArticle()
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
			$param['type'] = 'media';
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
			return $this->fetch();
		}
	}

	/**
	 * 设置显示
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 14:28
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setShow()
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
	 * @date 2018/12/24 14:27
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function setRecommend()
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
	 * 删除
	 * Created by：Mp_Lxj
	 * @date 2018/12/24 14:27
	 * @return array|\Illuminate\Http\JsonResponse|void
	 */
	public function delete()
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
}