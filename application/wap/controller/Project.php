<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 20:57
 */

namespace app\wap\controller;


use app\common\Msg;
use app\common\Projects;
use think\Cache;
use think\Request;

class Project extends Common
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('reuest_url','project/index');
    }

    /**
     * 在线课堂
     * created by:Mp_Lxj
     * @date:2019/1/8 21:02
     * @return mixed
     */
    public function index()
    {
        $param = Request::instance()->param();
        $this->assign('param',$param);
        $Projects = new Projects();
        $res = $Projects->project(true);
        if(Request::instance()->isAjax()){
            return trueAjax('',$res);
        }else{
            $this->assign('project',$res);
            $this->assign('site_title','在线课程');
            return $this->fetch();
        }

    }

    /**
     * 课程详情
     * Created by：Mp_Lxj
     * @date 2019/1/9 15:02
     * @return mixed
     */
    public function detail()
    {
        $param = Request::instance()->param();
        if(!$param['class']){
            $this->redirect('Index/index');
        }
        $Projects = new Projects();
        $res = $Projects->detail();
        $this->assign('detail',$res);
        $this->assign('is_project',true);
        $this->assign('site_title','课程详情');
        return $this->fetch();
    }

    /**
     * 收藏
     * Created by：Mp_Lxj
     * @date 2019/1/9 15:41
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function collect()
    {
        $Projects = new Projects();
        return $Projects->collect();
    }

    /**
     * 取消收藏
     * Created by：Mp_Lxj
     * @date 2019/1/9 15:41
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function delCollect()
    {
        $Projects = new Projects();
        return $Projects->delCollect();
    }

    /**
     * 发送短信
     * Created by：Mp_Lxj
     * @date 2019/1/11 10:27
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function getMsg()
    {
        $Msg = new Msg();
        $param = Request::instance()->param();
        $msg = $Msg->sendMsg($param['phone']);
//        $msg['status'] = 1;//调试
//        $msg['data'] = 123456;
        if($msg['status']){
            Cache::set('code_login_' . $param['phone'],$msg['data'],600);
            Cache::set('code_register_' . $param['phone'],$msg['data'],600);
            return trueAjax();
        }else{
            return $msg;
        }
    }

    /**
     * 验证码登录
     * Created by：Mp_Lxj
     * @date 2019/1/11 14:53
     */
    public function codeLogin()
    {
        $Login = new \app\common\Login();
        return $Login->codeLogin();
    }

    /**
     * 注册
     * created by:Mp_Lxj
     * @date:2019/1/14 20:56
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function register()
    {
        $Login = new \app\common\Login();
        return $Login->registerPost();
    }



    /**
     * 我的收藏
     * Created by：Mp_Lxj
     * @date 2019/1/15 9:20
     * @return mixed
     */
    public function collection()
    {
        $User = new \app\common\User();
        $res =  $User->collection(true);
        if(Request::instance()->isAjax()){
            return trueAjax('',$res);
        }else{
            $param = Request::instance()->param();
            $this->assign('param',$param);
            $this->assign('site_title','收藏课程');
            $this->assign('project',$res);
            return $this->fetch();
        }
    }
}