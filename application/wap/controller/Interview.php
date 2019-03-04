<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 22:01
 */

namespace app\wap\controller;


use think\Request;

class Interview extends Common
{
    public function index()
    {
        $Job = new \app\common\Job();
        $res = $Job->interview(true);

        if(Request::instance()->isAjax()){
            return trueAjax('',$res['video']['list']);
        }else{
            $this->assign('interview',$res);
            $this->assign('site_title','学员采访');
            return $this->fetch();
        }
    }
}