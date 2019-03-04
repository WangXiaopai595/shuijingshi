<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/21
 * Time: 21:45
 */

namespace app\admin\controller;


use app\model\WorkClass;
use think\Db;
use think\Request;

class Work extends Common
{
    /**
     * 作品列表
     * created by:Mp_Lxj
     * @date:2018/12/22 14:30
     * @return mixed
     */
    public function index()
    {
        $param = Request::instance()->param();
        $Work = new \app\model\Work();
        $WorkClass = new WorkClass();
        $work = $Work->workList($param);
        $class = $WorkClass->classList();
        $this->assign('class',$class);
        $this->assign('work',$work);
        $this->assign('param',$param);
        return $this->fetch();
    }

    /**
     * 添加作品
     * created by:Mp_Lxj
     * @date:2018/12/22 14:30
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addWork()
    {
        $Work = new \app\model\Work();
        $param = Request::instance()->param();
        if(Request::instance()->isAjax()){
            $file = Request::instance()->file();
            $path = uploadFile($file);
            if($path){
                foreach($path as $k=>$v){
                    $param[$k] = $v;
                }
            }
            if($param['type'] == 2){
                $video = getCC($param['video_id']);
                if(!$video['status']){
                    return falseAjax($video['msg'] ?: '获取视频信息失败');
                }
                foreach($video['data'] as $k=>$v){
                    $param[$k] = $v;
                }
            }else{
                unset($param['video_id']);
            }
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            $param['time'] = $param['time'] ? strtotime($param['time']) : time();
            $id = $Work->workInsert($param);
            if($id){
                return trueAjax('添加成功');
            }else{
                return falseAjax('添加失败');
            }
        }else{
            $WorkClass = new WorkClass();
            $class = $WorkClass->classList();
            $this->assign('class',$class);
            return $this->fetch();
        }
    }

    /**
     * 修改作品
     * created by:Mp_Lxj
     * @date:2018/12/22 14:30
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editWork()
    {
        $Work = new \app\model\Work();
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
            if($param['type'] == 2){
                $video = getCC($param['video_id']);
                if(!$video['status']){
                    return falseAjax($video['msg'] ?: '获取视频信息失败');
                }
                foreach($video['data'] as $k=>$v){
                    $param[$k] = $v;
                }
            }else{
                unset($param['video_id']);
            }
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            if($param['time']){
                $param['time'] = strtotime($param['time']);
            }else{
                unset($param['time']);
            }
            Db::startTrans();
            try{
                $Work->editWork($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $WorkClass = new WorkClass();
            $class = $WorkClass->classList();
            $work = $Work->getWorkDetail($map);
            $this->assign('class',$class);
            $this->assign('work',$work);
            return $this->fetch();
        }
    }

    /**
     * 删除作品
     * created by:Mp_Lxj
     * @date:2018/12/22 14:21
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $Work = new \app\model\Work();
        Db::startTrans();
        try{
            $Work->delWork($map);
            Db::commit();
            return trueAjax('删除成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 是否显示
     * created by:Mp_Lxj
     * @date:2018/12/22 21:02
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function setShow()
    {
        $data = Request::instance()->param();
        $map['id'] = $data['id'];
        $Work = new \app\model\Work();
        Db::startTrans();
        try{
            $Work->editWork($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}