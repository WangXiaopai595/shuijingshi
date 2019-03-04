<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 19:10
 */

namespace app\web\controller;


use think\Request;

class Job extends Common
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('reuest_url','job/index');
    }

    /**
     * 就业中心
     * created by:Mp_Lxj
     * @date:2019/1/9 19:32
     * @return mixed
     */
    public function index()
    {
        $Job = new \app\common\Job();
        $res = $Job->index(true);
        $this->assign('job',$res);
        $this->assign('site_title','就业中心');
        return $this->fetch();
    }

    /**
     * 明星学员、合作企业详情
     * created by:Mp_Lxj
     * @date:2019/1/9 20:56
     * @return mixed
     */
    public function detail()
    {
        $param = Request::instance()->param();
        if(!$param['article']){
            $this->redirect('Index/index');
        }

        $Job = new \app\common\Job();
        $res = $Job->article($param['article']);
        $this->assign('article',$res);
        $this->assign('site_title',$res['article']['title']);

        return $this->fetch();
    }

    /**
     * 明星学员
     * Created by：Mp_Lxj
     * @date 2019/1/10 9:36
     * @return mixed
     */
    public function startStudent()
    {
        $Job = new \app\common\Job();
        $res = $Job->student();
        $this->assign('article',$res);

        $this->assign('site_title','明星学员');
        return $this->fetch();
    }

    /**
     * 学员采访
     * Created by：Mp_Lxj
     * @date 2019/1/10 10:49
     * @return mixed
     */
    public function interview()
    {
        $Job = new \app\common\Job();
        $res = $Job->interview();
        $this->assign('interview',$res);

        $this->assign('site_title','学员采访');

        return $this->fetch();
    }

    /**
     * 合作企业
     * Created by：Mp_Lxj
     * @date 2019/1/10 11:07
     * @return mixed
     */
    public function company()
    {
        $Job = new \app\common\Job();
        $res = $Job->company();
        $this->assign('article',$res);

        $this->assign('site_title','合作企业');
        return $this->fetch();
    }
}