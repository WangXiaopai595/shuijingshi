<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/7
 * Time: 19:37
 */

namespace app\web\controller;

use think\Request;

class Index extends Common
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('reuest_url','index/index');
    }

    /**
     * 首页数据
     * Created by：Mp_Lxj
     * @date 2019/1/8 11:27
     * @return mixed
     */
    public function index()
    {
        $common = new \app\common\Index();
        $result = $common->index();
        $this->assign('data',$result);
        $this->assign('site_title','首页');
        return $this->fetch();
    }

    /**
     * 获取视频地址
     * Created by：Mp_Lxj
     * @date 2019/1/8 11:27
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function getVideo()
    {
        $param = Request::instance()->param();
        $path = getVideoUrl($param['video_id']);
        return trueAjax('',$path);
    }

    /**
     * 退出登录
     * Created by：Mp_Lxj
     * @date 2019/1/11 10:12
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function loginOut()
    {
        session('user',[]);
        return trueAjax();
    }

}