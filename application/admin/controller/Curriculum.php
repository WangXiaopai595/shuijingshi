<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/21
 * Time: 21:45
 */

namespace app\admin\controller;


use app\model\ClassList;
use app\model\ClassType;
use think\Db;
use think\Request;

class Curriculum extends Common
{
    /**
     * 课程列表
     * created by:Mp_Lxj
     * @date:2018/12/22 14:30
     * @return mixed
     */
    public function index()
    {
        $param = Request::instance()->param();
        $Curr = new \app\model\Curriculum();
        $ClassType = new ClassType();
        $class = $Curr->classList($param);
        $type['parent'] = $ClassType->classList([]);
        if($param['type_id']){
            $type['child'] = $ClassType->classList(['parent_id' => $param['type_id']]);
        }else{
            $type['child'] = [];
        }

        $this->assign('type',$type);
        $this->assign('class',$class);
        $this->assign('param',$param);
        return $this->fetch();
    }

    /**
     * 添加课程
     * created by:Mp_Lxj
     * @date:2018/12/22 14:30
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addCurriculum()
    {
        $Curr = new \app\model\Curriculum();
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
            $param['time'] = $param['time'] ? strtotime($param['time']) : time();
            $id = $Curr->classInsert($param);
            if($id){
                return trueAjax('添加成功');
            }else{
                return falseAjax('添加失败');
            }
        }else{
            $ClassType = new ClassType();
            $type['parent'] = $ClassType->classList([]);
            $this->assign('type',$type);
            return $this->fetch();
        }
    }

    /**
     * 修改课程
     * created by:Mp_Lxj
     * @date:2018/12/22 14:30
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editCurriculum()
    {
        $Curr = new \app\model\Curriculum();
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
            if($param['time']){
                $param['time'] = strtotime($param['time']);
            }else{
                unset($param['time']);
            }
            Db::startTrans();
            try{
                $Curr->editClass($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $ClassType = new ClassType();
            $type['parent'] = $ClassType->classList([]);
            $class = $Curr->getClassDetail($map);
            $type['child'] =  $ClassType->classList(['parent_id' => $class['type_id']]);
            $this->assign('class',$class);
            $this->assign('type',$type);
            return $this->fetch();
        }
    }

    /**
     * 删除课程
     * created by:Mp_Lxj
     * @date:2018/12/22 14:21
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $Curr = new \app\model\Curriculum();
        Db::startTrans();
        try{
            $Curr->delClass($map);
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
        $Curr = new \app\model\Curriculum();
        Db::startTrans();
        try{
            $Curr->editClass($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 课程列表
     * Created by：Mp_Lxj
     * @date 2018/12/24 11:11
     * @return mixed
     */
    public function curriculumList()
    {
        $param = Request::instance()->param();
        $this->assign('param',$param);

        $map['id'] = $param['class_id'];
        $Curr = new \app\model\Curriculum();
        $curr = $Curr->getClassDetail($map);
        $this->assign('curr',$curr);

        $ClassList = new ClassList();
        $param['type'] = 1;
        $class = $ClassList->classList($param);
        $this->assign('class',$class);
        return $this->fetch();
    }

    /**
     * 添加课程列表
     * Created by：Mp_Lxj
     * @date 2018/12/24 11:11
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function insertCurriculum()
    {
        $ClassList = new ClassList();
        $param = Request::instance()->param();
        if(Request::instance()->isAjax()){
            $param['type'] = 1;
            $video = getCC($param['video_id']);
            if(!$video['status']){
                return falseAjax($video['msg'] ?: '获取视频信息失败');
            }
            unset($video['data']['down_url']);
            foreach($video['data'] as $k=>$v){
                $param[$k] = $v;
            }
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            $id = $ClassList->classInsert($param);
            if($id){
                return trueAjax('添加成功');
            }else{
                return falseAjax('添加失败');
            }
        }else{
            $this->assign('param',$param);
            return $this->fetch();
        }
    }

    /**
     * 修改课程列表
     * Created by：Mp_Lxj
     * @date 2018/12/24 11:10
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function updateCurriculum()
    {
        $ClassList = new ClassList();
        $param = Request::instance()->param();
        $map['id'] = $param['id'];
        if(Request::instance()->isAjax()){
            $param['type'] =  1;
            $video = getCC($param['video_id']);
            if(!$video['status']){
                return falseAjax($video['msg'] ?: '获取视频信息失败');
            }
            unset($video['data']['down_url']);
            foreach($video['data'] as $k=>$v){
                $param[$k] = $v;
            }
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            Db::startTrans();
            try{
                $ClassList->editClass($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $class = $ClassList->getClassDetail($map);
            $this->assign('class',$class);
            return $this->fetch();
        }
    }

    /**
     * 删除课程
     * Created by：Mp_Lxj
     * @date 2018/12/24 11:10
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function delCurriculum()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $ClassList = new ClassList();
        Db::startTrans();
        try{
            $ClassList->delClass($map);
            Db::commit();
            return trueAjax('删除成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 排序
     * Created by：Mp_Lxj
     * @date 2018/12/24 11:10
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function sort()
    {
        $data = Request::instance()->param();
        $ClassList = new ClassList();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $ClassList->editClass($map,$arr);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 设置是否显示
     * Created by：Mp_Lxj
     * @date 2018/12/24 11:10
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function show()
    {
        $data = Request::instance()->param();
        $map['id'] = $data['id'];
        $ClassList = new ClassList();
        Db::startTrans();
        try{
            $ClassList->editClass($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}