<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/21
 * Time: 19:08
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Banner extends Common
{
    /**
     * banner列表
     * created by:Mp_Lxj
     * @date:2018/12/21 19:27
     * @return mixed
     */
    public function index()
    {
        $param = Request::instance()->param();
        $Banner = new \app\model\Banner();
        $banner = $Banner->bannerList();
        $this->assign('banner',$banner);
        $this->assign('param',$param);
        return $this->fetch();
    }

    /**
     * 添加banner
     * created by:Mp_Lxj
     * @date:2018/12/21 19:27
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function addBanner()
    {
        if(Request::instance()->isAjax()){
            $banner = new \app\model\Banner();
            $param = Request::instance()->param();
            $file = Request::instance()->file();
            $path = uploadFile($file);
            if($path){
                foreach($path as $k=>$v){
                    $param[$k] = $v;
                }
            }
            $id = $banner->bannerInsert($param);
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
     * 修改Banner
     * created by:Mp_Lxj
     * @date:2018/12/21 19:29
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function bannerDetail()
    {
        $param = Request::instance()->param();
        $map['id'] = $param['id'];
        $Banner = new \app\model\Banner();
        if(Request::instance()->isAjax()){
            $file = Request::instance()->file();
            $path = uploadFile($file);
            if($path){
                foreach($path as $k=>$v){
                    $param[$k] = $v;
                }
            }
            Db::startTrans();
            try{
                $Banner->editBanner($map,$param);
                Db::commit();
                return trueAjax('修改成功');
            }catch(\Exception $e){
                Db::rollback();
                return falseAjax($e->getMessage());
            }
        }else{
            $banner = $Banner->getBannerDetail($map);
            $this->assign('banner',$banner);
            return $this->fetch();
        }
    }

    /**
     * 删除Banner
     * created by:Mp_Lxj
     * @date:2018/12/21 19:23
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $param = Request::instance()->param();
        $map['id'] = ['in',$param['id']];
        $Banner = new \app\model\Banner();
        Db::startTrans();
        try{
            $Banner->delBanner($map);
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
     * @date:2018/12/21 19:24
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sort()
    {
        $data = Request::instance()->param();
        $Banner = new \app\model\Banner();
        $groupID = explode(',',$data['id']);
        $groupSort = explode(',',$data['sort']);
        Db::startTrans();
        try{
            foreach($groupID as $k=>$v){
                $map['id'] = $v;
                $arr['sort'] = $groupSort[$k];
                $Banner->editBanner($map,$arr);
            }
            Db::commit();
            return trueAjax('更新成功');
        }catch(\Exception $e){
            Db::rollback();
            return falseAjax($e->getMessage());
        }
    }
}