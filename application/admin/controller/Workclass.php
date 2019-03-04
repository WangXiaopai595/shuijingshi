<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/21
 * Time: 21:53
 */

namespace app\admin\controller;


use app\model\Work;
use think\Db;
use think\Request;

class Workclass extends Common
{
    /**
     * 作品分类列表
     * created by:Mp_Lxj
     * @date:2018/12/21 22:07
     * @return mixed
     */
    public function index()
    {
        $WorkClass = new \app\model\WorkClass();
        $class = $WorkClass->classList();
        $this->assign('class',$class);
        return $this->fetch();
    }

    /**
     * 添加作品分类
     * created by:Mp_Lxj
     * @date:2018/12/21 22:07
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addClass()
    {
        $WorkClass = new \app\model\WorkClass();
        $param = Request::instance()->param();
        if(Request::instance()->isAjax()){
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            $id = $WorkClass->classInsert($param);
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
     * 修改作品分类
     * created by:Mp_Lxj
     * @date:2018/12/21 22:07
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editClass()
    {
        $WorkClass = new \app\model\WorkClass();
        $param = Request::instance()->param();
        $map['id'] = $param['id'];
        if(Request::instance()->isAjax()){
            Db::startTrans();
            try{
                if(!$param['is_show']){
                    $param['is_show'] = 0;
                }
                $WorkClass->editClass($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $class = $WorkClass->getClassDetail($map);
            $this->assign('class',$class);
            return $this->fetch();
        }
    }

    /**
     * 排序
     * created by:Mp_Lxj
     * @date:2018/12/21 22:08
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sortClass()
    {
        $data = Request::instance()->param();
        $WorkClass = new \app\model\WorkClass();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $WorkClass->editClass($map,$arr);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 删除作品分类
     * created by:Mp_Lxj
     * @date:2018/12/21 22:08
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $workMap['class_id'] = ['in',$param['id']];
        $WorkClass = new \app\model\WorkClass();
        $Work = new Work();
        Db::startTrans();
        try{
            $WorkClass->delClass($map);
            $Work->delWork($workMap);
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
        $WorkClass = new \app\model\WorkClass();
        Db::startTrans();
        try{
            $WorkClass->editClass($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}