<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 19:17
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Teacher extends Common
{
    /**
     * 教师列表
     * created by:Mp_Lxj
     * @date:2018/12/25 19:33
     * @return mixed
     */
    public function index()
    {
        $param = Request::instance()->param();
        $this->assign('param',$param);
        $Teacher = new \app\model\Teacher();
        $teacher = $Teacher->teacherList($param);
        $this->assign('teacher',$teacher);
        return $this->fetch();
    }

    /**
     * 添加教师
     * created by:Mp_Lxj
     * @date:2018/12/25 19:33
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addTeacher()
    {
        $Teacher = new \app\model\Teacher();
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
            $param['time'] = time();
            $id = $Teacher->teacherInsert($param);
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
     * 修改教师
     * created by:Mp_Lxj
     * @date:2018/12/25 19:33
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editTeacher()
    {
        $Teacher = new \app\model\Teacher();
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
                $Teacher->ediTteacher($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $class = $Teacher->getTeacherDetail($map);
            $this->assign('teacher',$class);
            return $this->fetch();
        }
    }

    /**
     * 排序
     * created by:Mp_Lxj
     * @date:2018/12/25 19:33
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sort()
    {
        $data = Request::instance()->param();
        $Teacher = new \app\model\Teacher();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $Teacher->ediTteacher($map,$arr);
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
     * created by:Mp_Lxj
     * @date:2018/12/25 19:33
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function setShow()
    {
        $data = Request::instance()->param();
        $map['id'] = $data['id'];
        $Teacher = new \app\model\Teacher();
        Db::startTrans();
        try{
            $Teacher->ediTteacher($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 删除
     * created by:Mp_Lxj
     * @date:2018/12/25 19:32
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $Teacher = new \app\model\Teacher();
        Db::startTrans();
        try{
            $Teacher->delTeacher($map);
            Db::commit();
            return trueAjax('删除成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}