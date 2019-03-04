<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 20:17
 */

namespace app\admin\controller;


use app\model\Article;
use think\Db;
use think\Request;

class About extends Common
{
    /**
     * 公司介绍
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return mixed
     */
    public function introduce()
    {
        $Article = new Article();
        $map['type'] = 'introduce';
        $article = $Article->getArticleDetail($map);
        $this->assign('article',$article);
        return $this->fetch();
    }

    /**
     * 更新公司介绍
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function editIntroduce()
    {
        $param = Request::instance()->param();
        $Article = new Article();
        $param['type'] = 'introduce';

        Db::startTrans();
        try{
            if(!$param['id']){
                unset($param['id']);
                $param['time'] = time();
                $Article->articleInsert($param);
            }else{
                $map['id'] = $param['id'];
                $Article->editArticle($map,$param);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 开班时间
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return mixed
     */
    public function classTime()
    {
        $Article = new Article();
        $map['type'] = 'classtime';
        $article = $Article->getArticleDetail($map);
        $this->assign('article',$article);
        return $this->fetch();
    }

    /**
     * 更新开班时间
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function editClassTime()
    {
        $param = Request::instance()->param();
        $Article = new Article();
        $param['type'] = 'classtime';

        Db::startTrans();
        try{
            if(!$param['id']){
                unset($param['id']);
                $param['time'] = time();
                $Article->articleInsert($param);
            }else{
                $map['id'] = $param['id'];
                $Article->editArticle($map,$param);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 付款方式
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return mixed
     */
    public function payMode()
    {
        $Article = new Article();
        $map['type'] = 'paymode';
        $article = $Article->getArticleDetail($map);
        $this->assign('article',$article);
        return $this->fetch();
    }

    /**
     * 更新付款方式
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function editPayMode()
    {
        $param = Request::instance()->param();
        $Article = new Article();
        $param['type'] = 'paymode';

        Db::startTrans();
        try{
            if(!$param['id']){
                unset($param['id']);
                $param['time'] = time();
                $Article->articleInsert($param);
            }else{
                $map['id'] = $param['id'];
                $Article->editArticle($map,$param);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 来校路线
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return mixed
     */
    public function route()
    {
        $Article = new Article();
        $map['type'] = 'route';
        $article = $Article->getArticleDetail($map);
        $this->assign('article',$article);
        return $this->fetch();
    }

    /**
     * 更新来校线路
     * created by:Mp_Lxj
     * @date:2018/12/25 20:27
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function editRoute()
    {
        $param = Request::instance()->param();
        $Article = new Article();
        $param['type'] = 'route';

        Db::startTrans();
        try{
            if(!$param['id']){
                unset($param['id']);
                $param['time'] = time();
                $Article->articleInsert($param);
            }else{
                $map['id'] = $param['id'];
                $Article->editArticle($map,$param);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}