<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/22
 * Time: 15:49
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Menu extends Common
{
    /**
     * 菜单列表
     * created by:Mp_Lxj
     * @date:2018/12/22 16:06
     * @return mixed
     */
    public function index()
    {
        $Menu = new \app\model\Menu();
        $menu = $Menu->menuList();
        $this->assign('menu',$menu);
        return $this->fetch();
    }

    /**
     * 添加菜单
     * created by:Mp_Lxj
     * @date:2018/12/22 16:05
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addMenu()
    {
        $Menu = new \app\model\Menu();
        $param = Request::instance()->param();
        if(Request::instance()->isAjax()){
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            $id = $Menu->menuInsert($param);
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
     * 修改菜单
     * created by:Mp_Lxj
     * @date:2018/12/22 16:05
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function editMenu()
    {
        $Menu = new \app\model\Menu();
        $param = Request::instance()->param();
        $map['id'] = $param['id'];
        if(Request::instance()->isAjax()){
            if(!$param['is_show']){
                $param['is_show'] = 0;
            }
            Db::startTrans();
            try{
                $Menu->editMenu($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $class = $Menu->getMenuDetail($map);
            $this->assign('menu',$class);
            return $this->fetch();
        }
    }

    /**
     * 排序
     * created by:Mp_Lxj
     * @date:2018/12/22 16:05
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sort()
    {
        $data = Request::instance()->param();
        $Menu = new \app\model\Menu();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $Menu->editMenu($map,$arr);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }

    /**
     * 删除菜单
     * created by:Mp_Lxj
     * @date:2018/12/22 16:05
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $Menu = new \app\model\Menu();
        Db::startTrans();
        try{
            $Menu->delMenu($map);
            Db::commit();
            return trueAjax('删除成功');
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
    public function setStatus()
    {
        $data = Request::instance()->param();
        $map['id'] = $data['id'];
        $Menu = new \app\model\Menu();
        Db::startTrans();
        try{
            $Menu->editMenu($map,$data);
            Db::commit();
            return trueAjax('修改成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}