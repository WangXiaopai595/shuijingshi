<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/22
 * Time: 17:45
 */

namespace app\admin\controller;


use app\model\CourseClass;
use app\model\CourseControl;
use app\model\CourseDetail;
use think\Db;
use think\Request;

class Course extends Common
{
    /**
     * 精品课程分类列表
     * created by:Mp_Lxj
     * @date:2018/12/22 18:16
     * @return mixed
     */
    public function courseClass()
    {
        $Class = new CourseClass();
        $class = $Class->classList();
        $this->assign('class',$class);
        return $this->fetch();
    }

    /**
     * 添加分类
     * created by:Mp_Lxj
     * @date:2018/12/22 18:18
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addClass()
    {
        $Class = new CourseClass();
        if(Request::instance()->isAjax()){
            $param = Request::instance()->param();
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
            $id = $Class->classInsert($param);
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
     * 修改精品课程分类
     * created by:Mp_Lxj
     * @date:2018/12/22 18:20
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editClass()
    {
        $Class = new CourseClass();
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
            Db::startTrans();
            try{
                $Class->editClass($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $user = $Class->getClassDetail($map);
            $this->assign('class',$user);
            return $this->fetch();
        }
    }

    /**
     * 删除精品课程份分类
     * created by:Mp_Lxj
     * @date:2018/12/22 18:21
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delClass()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $Class = new CourseClass();
        Db::startTrans();
        try{
            $Class->delClass($map);
            Db::commit();
            return trueAjax('删除成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 更新排序
     * created by:Mp_Lxj
     * @date:2018/12/22 18:23
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sortClass()
    {
        $data = Request::instance()->param();
        $Class = new CourseClass();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $Class->editClass($map,$arr);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 修改菜单状态
     * created by:Mp_Lxj
     * @date:2018/12/22 16:14
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function setShow()
    {
        $data = Request::instance()->param();
        $map['id'] = $data['id'];
        $Class = new CourseClass();
        Db::startTrans();
        try{
            $Class->editClass($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 精品课程图片详情
     * created by:Mp_Lxj
     * @date:2018/12/22 20:13
     * @return mixed
     */
    public function imgList()
    {
        $param = Request::instance()->param();
        $this->assign('param',$param);
        $map['id'] = $param['class_id'];
        $CourseClass = new CourseClass();
        $class = $CourseClass->getClassDetail($map);
        $this->assign('class',$class);

        $CourseDetail = new CourseDetail();
        $img = $CourseDetail->courseList($param);
        $this->assign('img',$img);
        return $this->fetch();
    }

    /**
     * 添加详情图片
     * created by:Mp_Lxj
     * @date:2018/12/22 20:13
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addImg()
    {
        $param = Request::instance()->param();
        if(Request::instance()->isAjax()){
            $CourseDetail = new CourseDetail();
            $file = Request::instance()->file();
            $path = uploadFile($file);
            if($path){
                foreach($path as $k=>$v){
                    $param[$k] = $v;
                }
            }
            $param['time'] = time();
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            $id = $CourseDetail->courseInsert($param);
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
     * 修改详情图片
     * created by:Mp_Lxj
     * @date:2018/12/22 20:14
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editImg()
    {
        $param = Request::instance()->param();
        $map['id'] = $param['id'];
        $CourseDetail = new CourseDetail();
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
            Db::startTrans();
            try{
                $CourseDetail->editCourse($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $img = $CourseDetail->getCourseDetail($map);
            $this->assign('img',$img);
            return $this->fetch();
        }
    }

    /**
     * 详情图片排序
     * created by:Mp_Lxj
     * @date:2018/12/22 20:17
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sortImg()
    {
        $data = Request::instance()->param();
        $CourseDetail = new CourseDetail();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $CourseDetail->editCourse($map,$arr);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 删除详情图片
     * created by:Mp_Lxj
     * @date:2018/12/22 20:17
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delImg()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $CourseDetail = new CourseDetail();
        Db::startTrans();
        try{
            $CourseDetail->delCourse($map);
            Db::commit();
            return trueAjax('删除成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 设置是否显示
     * created by:Mp_Lxj
     * @date:2018/12/22 20:18
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function setShowImg()
    {
        $data = Request::instance()->param();
        $map['id'] = $data['id'];
        $CourseDetail = new CourseDetail();
        Db::startTrans();
        try{
            $CourseDetail->editCourse($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 控件列表
     * created by:Mp_Lxj
     * @date:2018/12/22 21:35
     * @return mixed
     */
    public function control()
    {
        $param = Request::instance()->param();
        $map['id'] = $param['class_id'];
        $CourseClass = new CourseClass();
        $class = $CourseClass->getClassDetail($map);
        $this->assign('class',$class);

        $Control = new CourseControl();
        $control = $Control->courseList($param);
        $this->assign('control',$control);
        return $this->fetch();
    }

    /**
     * 添加控件内容
     * created by:Mp_Lxj
     * @date:2018/12/22 21:45
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function controlAdd()
    {
        $Control = new CourseControl();
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
            }
            $id = $Control->courseInsert($param);
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
     * 修改控件内容
     * created by:Mp_Lxj
     * @date:2018/12/22 21:45
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function controlEdit()
    {
        $Control = new CourseControl();
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
            Db::startTrans();
            try{
                $Control->editCourse($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $control = $Control->getCourseDetail($map);
            $this->assign('control',$control);
            $this->assign('param',$param);
            return $this->fetch();
        }
    }

    /**
     * 删除控件内容
     * created by:Mp_Lxj
     * @date:2018/12/22 21:48
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delControl()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $workMap['class_id'] = ['in',$param['id']];
        $Control = new CourseControl();
        Db::startTrans();
        try{
            $Control->delCourse($map);
            Db::commit();
            return trueAjax('删除成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 排序
     * created by:Mp_Lxj
     * @date:2018/12/22 21:47
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function controlSort()
    {
        $data = Request::instance()->param();
        $Control = new CourseControl();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $Control->editCourse($map,$arr);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}