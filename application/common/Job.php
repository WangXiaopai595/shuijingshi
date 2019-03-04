<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 19:10
 */

namespace app\common;


use app\model\Article;
use app\model\Banner;
use app\model\VideoList;
use think\Request;

class Job
{
    /**
     * 就业中心
     * created by:Mp_Lxj
     * @date:2019/1/9 19:30
     * @return mixed
     */
    public function index($is_pc = false)
    {
        //banner图
        $Banner = new Banner();
        $banner_map['mode'] = '学员作品';
        $res['banner'] = $Banner->getBannerFirst($banner_map);

        $field = ['id','icon','title','brief','time'];
        //明星学员
        $Article = new Article();
        $map['type'] = $Article::TYPE_STUDENT;
        $map['is_show'] = $Article::IS_SHOW_TRUE;
        $res['student'] = $Article->getArticle($map,'0,4',$field);

        //合作企业
        $company_map['type'] = $Article::TYPE_COMPANY;
        $company_map['is_show'] = $Article::IS_SHOW_TRUE;
        $res['company'] = $Article->getArticle($company_map,'0,4',$field);

        //学员采访
        $Video = new VideoList();
        $video_map['type'] = 1;
        $video_map['is_show'] = $Video::IS_SHOW_TRUE;
        if($is_pc){
            $res['video'] = $Video->getVideo($video_map,'0,6');
        }else{
            $res['video'] = $Video->getVideo($video_map,'0,4');
        }

        return $res;
    }

    /**
     * 文章详情及上下条
     * created by:Mp_Lxj
     * @date:2019/1/9 20:45
     * @param $id
     * @param $type
     * @return mixed
     */
    public function article($id)
    {
        $Article = new Article();
        $map['id'] = $id;
        $map['is_show'] = $Article::IS_SHOW_TRUE;
        $res['article'] = $Article->getArticleDetail($map);
        $Article->peopleInc($map);

        //下一条
        $nex_map['type'] = ['=',$res['article']['type']];
        $nex_map['time'] = ['<=',$res['article']['time']];
        $nex_map['id'] = ['<>',$res['article']['id']];
        if($res['article']['is_recommend'] == 1){
            $nex_map['is_recommend'] = ['=',$res['article']['is_recommend']];
            $res['nex'] = $Article->getArticleFirst($nex_map,['id','title']);
            if(!$res['nex']){
                $nex_map['is_recommend'] = ['<',$res['article']['is_recommend']];
                unset($nex_map['time']);
                $res['nex'] = $Article->getArticleFirst($nex_map,['id','title']);
            }
        }else{
            $nex_map['is_recommend'] = ['=',$res['article']['is_recommend']];
            $res['nex'] = $Article->getArticleFirst($nex_map,['id','title']);
        }

        //上一条
        $per_map['type'] = ['=',$res['article']['type']];
        $per_map['time'] = ['>=',$res['article']['time']];
        $per_map['id'] = ['<>',$res['article']['id']];
        if($res['article']['is_recommend'] == 1){
            $per_map['is_recommend'] = ['=',$res['article']['is_recommend']];
            $res['per'] = $Article->getArticleFirst($per_map,['id','title'],'is_recommend,time,id');
        }else{
            $per_map['is_recommend'] = ['=',$res['article']['is_recommend']];
            $res['per'] = $Article->getArticleFirst($per_map,['id','title'],'is_recommend,time,id');
            if(!$res['per']){
                $per_map['is_recommend'] = ['>',$res['article']['is_recommend']];
                unset($per_map['time']);
                $res['per'] = $Article->getArticleFirst($per_map,['id','title'],'is_recommend,time,id');
            }
        }
        return $res;
    }

    /**
     * 明星学员
     * Created by：Mp_Lxj
     * @date 2019/1/10 9:34
     * @return mixed
     */
    public function student()
    {
        //banner图
        $Banner = new Banner();
        $banner_map['mode'] = '明星学员';
        $res['banner'] = $Banner->getBannerFirst($banner_map);

        $field = ['id','icon','title','brief','time'];
        //明星学员
        $Article = new Article();
        $map['type'] = $Article::TYPE_STUDENT;
        $map['is_show'] = $Article::IS_SHOW_TRUE;
        $res['student'] = $Article->getArticlePage($map,$field);

        $res['count'] = $Article->getCount($map);

        return $res;
    }

    /**
     * 学员采访
     * Created by：Mp_Lxj
     * @date 2019/1/10 10:48
     * @return mixed
     */
    public function interview($is_mobile = false)
    {
        //banner图
        $Banner = new Banner();
        $banner_map['mode'] = '学员采访';
        $res['banner'] = $Banner->getBannerFirst($banner_map);

        //学员采访
        $Video = new VideoList();
        $video_map['type'] = 1;
        $video_map['is_show'] = $Video::IS_SHOW_TRUE;
        $paginate = $is_mobile ? 8 : 9;
        $res['video'] = $Video->getVideoPage($video_map,'*','is_recommend desc,sort,time desc,id desc',$paginate);

        $res['count'] = $Video->getCount($video_map);
        return $res;
    }

    /**
     * 合作企业
     * Created by：Mp_Lxj
     * @date 2019/1/10 11:06
     * @return mixed
     */
    public function company()
    {
        //banner图
        $Banner = new Banner();
        $banner_map['mode'] = '合作企业';
        $res['banner'] = $Banner->getBannerFirst($banner_map);

        $field = ['id','icon','title','brief','time'];
        $Article = new Article();
        $map['type'] = $Article::TYPE_STUDENT;
        $map['is_show'] = $Article::IS_SHOW_TRUE;

        //合作企业
        $company_map['type'] = $Article::TYPE_COMPANY;
        $company_map['is_show'] = $Article::IS_SHOW_TRUE;
        $res['company'] = $Article->getArticlePage($company_map,$field);

        $res['count'] = $Article->getCount($company_map);
        return $res;
    }
}