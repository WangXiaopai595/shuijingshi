<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 21:00
 */

namespace app\common;


use app\model\ClassCollection;
use app\model\ClassList;
use app\model\ClassType;
use app\model\Curriculum;
use think\Request;

class Projects
{
    /**
     * 课程列表
     * created by:Mp_Lxj
     * @date:2019/1/8 21:17
     * @return mixed
     */
    public function project($is_mobile = false)
    {
        $param = Request::instance()->param();

        $classType = new ClassType();
        //一级
        $map['is_show'] = $classType::IS_SHOW_TRUE;
        $map['level'] = 1;
        $res['type'] = $classType->getClassList($map);

        if(!$is_mobile){
            //二级
            $map['parent_id'] = $param['type_id'];
            $map['level'] = 2;
            $res['type_child'] = $classType->getClassList($map);
        }

        //课程
        $Class = new Curriculum();
        if($param['type_id']){
            $project_map['type_id'] = $param['type_id'];
        }
        if($param['child_id']){
            $project_map['child_id'] = $param['child_id'];
        }
        $project_map['t.is_show'] = $Class::IS_SHOW_TRUE;
        $project_map['t1.is_show'] = $Class::IS_SHOW_TRUE;
        $project_map['t2.is_show'] = $Class::IS_SHOW_TRUE;
        $res['class'] = $Class->getClassList($project_map,$param);

        $res['count'] = $Class->getCount($project_map);
        return $res;
    }

    /**
     * 课程详情
     * Created by：Mp_Lxj
     * @date 2019/1/9 13:52
     */
    public function detail()
    {
        $param = Request::instance()->param();
        //课程
        $Class = new Curriculum();
        $map['id'] = $param['class'];
        $project_map['is_show'] = $Class::IS_SHOW_TRUE;
        $res['class'] = $Class->getClassDetail($map);
        $Class->peopleInc($project_map);

        //是否已收藏
        $user = session('user');
        if($user){
            $collect_map['uid'] = $user['id'];
            $collect_map['class_id'] = $param['class'];
            $ClassCollection = new ClassCollection();
            $count = $ClassCollection->getCount($collect_map);
            $res['class']['is_collect'] = $count;
        }else{
            $res['class']['is_collect'] = 0;
        }

        //播放时长
        $ClassList = new ClassList();
        $list_map['class_id'] = $param['class'];
        $list_map['is_show'] = $ClassList::IS_SHOW_TRUE;
        $list = $ClassList->getClassList($list_map);
        $res['list'] = $list;
        return $res;
    }

    /**
     * 收藏
     * Created by：Mp_Lxj
     * @date 2019/1/9 15:40
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function collect()
    {
        $user = session('user');
        if(!$user){
            return falseAjax('请先登录');
        }

        $param = Request::instance()->param();
        if(!$param['class']){
            return falseAjax('参数错误');
        }
        $data['class_id'] = $param['class'];
        $data['uid'] = $user['id'];

        $ClassCollection = new ClassCollection();
        $ClassCollection->insertCollect($data);
        return trueAjax('收藏成功');
    }

    /**
     * 删除收藏
     * Created by：Mp_Lxj
     * @date 2019/1/9 15:41
     * @return array|\Illuminate\Http\JsonResponse|void
     */
    public function delCollect()
    {
        $user = session('user');
        if(!$user){
            return falseAjax('请先登录');
        }

        $param = Request::instance()->param();
        if(!$param['class']){
            return falseAjax('参数错误');
        }
        $map['class_id'] = $param['class'];
        $map['uid'] = $user['id'];

        $ClassCollection = new ClassCollection();
        $res = $ClassCollection->delCollect($map);
        if($res){
            return trueAjax('取消成功');
        }else{
            return trueAjax('取消失败');
        }
    }
}