<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/23
 * Time: 16:12
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Classtype extends Common
{
    /**
     * 分类类别
     * created by:Mp_Lxj
     * @date:2018/12/23 16:33
     * @return mixed
     */
    public function index()
    {
        $param = Request::instance()->param();
        $this->assign('param',$param);
        $Class = new \app\model\ClassType();
        $class = $Class->classList($param);
        $this->assign('class',$class);
        return $this->fetch();
    }

    /**
     * 添加分类
     * created by:Mp_Lxj
     * @date:2018/12/23 16:33
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addType()
    {
        $Class = new \app\model\ClassType();
        $param = Request::instance()->param();
        if(Request::instance()->isAjax()){
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
            $this->assign('param',$param);
            return $this->fetch();
        }
    }

    /**
     * 修改分类
     * created by:Mp_Lxj
     * @date:2018/12/23 16:33
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editType()
    {
        $Class = new \app\model\ClassType();
        $param = Request::instance()->param();
        $map['id'] = $param['id'];
        if(Request::instance()->isAjax()){
            Db::startTrans();
            try{
                if(!$param['is_show']){
                    $param['is_show'] = 0;
                }
                $Class->editClass($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $class = $Class->getClassDetail($map);
            $this->assign('class',$class);
            $this->assign('param',$param);
            return $this->fetch();
        }
    }

    /**
     * 删除分类
     * created by:Mp_Lxj
     * @date:2018/12/23 16:33
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delType()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $workMap['class_id'] = ['in',$param['id']];
        $Class = new \app\model\ClassType();
        $Curr = new \app\model\Curriculum();
        $typeMap['parent_id'] = ['in',$param['id']];
        $currMap['type_id'] = ['in',$param['id']];
        Db::startTrans();
        try{
            $Class->delClass($map);
            $Class->delClass($typeMap);
            $Curr->delClass($currMap);
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
     * @date:2018/12/23 16:33
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sort()
    {
        $data = Request::instance()->param();
        $Class = new \app\model\ClassType();
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
     * 设置是否显示
     * created by:Mp_Lxj
     * @date:2018/12/23 16:33
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function setShow()
    {
        $data = Request::instance()->param();
        $map['id'] = $data['id'];
        $Class = new \app\model\ClassType();
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
     * 科目列表
     * created by:Mp_Lxj
     * @date:2018/12/23 16:32
     * @return mixed
     */
    public function subject()
    {
        $param = Request::instance()->param();
        $map['id'] = $param['parent_id'];
        $Class = new \app\model\ClassType();
        $type = $Class->getClassDetail($map);
        $this->assign('type',$type);

        $class = $Class->classList($param);
        $this->assign('class',$class);

        $this->assign('param',$param);
        return $this->fetch();
    }
}