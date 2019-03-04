<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/10
 * Time: 11:59
 */

namespace app\common;


use app\model\Ambient;
use app\model\Article;
use app\model\Banner;
use app\model\Cases;
use app\model\NewClass;
use app\model\Story;
use app\model\Teacher;
use app\model\VideoList;
use think\Request;

class About
{
	/**
	 * 关于我们
	 * Created by：Mp_Lxj
	 * @date 2019/1/10 16:40
	 * @return mixed
	 */
	public function index()
	{
		//banner图
		$Banner = new Banner();
		$banner_map['mode'] = '关于我们';
		$res['banner'] = $Banner->getBannerFirst($banner_map);

		$field = ['id','icon','title','brief','time'];
		//学院荣誉
		$Article = new Article();
		$map['type'] = $Article::TYPE_HONOR;
		$map['is_show'] = $Article::IS_SHOW_TRUE;
		$res['honor'] = $Article->getArticle($map,'0,6',$field);

		//媒体采访
		$Article = new Article();
		$map['type'] = $Article::TYPE_MEDIA;
		$res['media'] = $Article->getArticle($map,'0,6',$field);

		//学生战绩
		$Article = new Article();
		$map['type'] = $Article::TYPE_RECORD;
		$res['record'] = $Article->getArticle($map,'0,8',$field);

		//授权认证
		$Article = new Article();
		$map['type'] = $Article::TYPE_GRANT;
		$res['grant'] = $Article->getArticle($map,'0,3',$field);

		//招聘会回顾
		$Article = new Article();
		$map['type'] = $Article::TYPE_RECRUIT;
		$res['recruit'] = $Article->getArticle($map,'0,9',$field);

		//CG交流活动
		$Article = new Article();
		$map['type'] = $Article::TYPE_CG;
		$res['cg'] = $Article->getArticle($map,'0,12',$field);

		return $res;
	}

	/**
	 * 品牌故事
	 * created by:Mp_Lxj
	 * @date:2019/1/10 20:07
	 * @return mixed
	 */
	public function story()
	{
		//banner图
		$Banner = new Banner();
		$banner_map['mode'] = '品牌故事';
		$res['banner'] = $Banner->getBannerFirst($banner_map);

		//媒体报道
		$Video = new VideoList();
		$video_map['type'] = 2;
		$video_map['is_show'] = $Video::IS_SHOW_TRUE;
		$res['video'] = $Video->getVideo($video_map,'0,6');

		//国际案例
		$Case = new Cases();
		$case_map['is_show'] = $Case::IS_SHOW_TRUE;
		$res['case'] = $Case->getCaseList($case_map);
		foreach($res['case'] as &$v){
			$case_video_map['case_id'] = $v['id'];
			$case_video_map['type'] = 3;
			$case_video_map['is_show'] = $Video::IS_SHOW_TRUE;
			$v['video'] = $Video->getVideo($case_video_map,'0,3');
		}

		//成长历程
		$Story = new Story();
		$res['story'] = $Story->storyList();
		foreach($res['story'] as &$v){
			$brief = nl2br($v['brief']);
			$v['brief'] = explode('<br />',$brief);
		}
		return $res;
	}

	/**
	 * 公司介绍
	 * created by:Mp_Lxj
	 * @date:2019/1/10 20:46
	 * @return mixed
	 */
	public function introduce()
	{
		//banner图
		$Banner = new Banner();
		$banner_map['mode'] = '公司介绍';
		$res['banner'] = $Banner->getBannerFirst($banner_map);

		$field = ['id','icon','title','brief','time'];
		//公司介绍
		$Article = new Article();
		$map['type'] = $Article::TYPE_INTRODUCE;
		$map['is_show'] = $Article::IS_SHOW_TRUE;
		$res['article'] = $Article->getArticlePage($map,$field);

		$res['count'] = $Article->getCount($map);
		return $res;
	}

	/**
	 * 师资团队
	 * created by:Mp_Lxj
	 * @date:2019/1/10 21:07
	 * @return mixed
	 */
	public function teacher()
	{
		//banner图
		$Banner = new Banner();
		$banner_map['mode'] = '师资团队';
		$res['banner'] = $Banner->getBannerFirst($banner_map);

		//师资团队
		$Teacher = new Teacher();
		$map['type'] = 1;
		$map['is_show'] = $Teacher::IS_SHOW_TRUE;
		$res['teacher_1'] = $Teacher->getTeacherAll($map);

		//专家
		$map['type'] = 2;
		$res['teacher_2'] = $Teacher->getTeacherAll($map);
		return $res;
	}

	/**
	 * 学院环境
	 * created by:Mp_Lxj
	 * @date:2019/1/10 21:29
	 * @param $type
	 * @return mixed
	 */
	public function ambient($type)
	{
		//banner图
		$Banner = new Banner();
		if($type == 1){
			$banner_map['mode'] = '学院环境';
		}else{
			$banner_map['mode'] = '宿舍环境';
		}
		$res['banner'] = $Banner->getBannerFirst($banner_map);

		//环境
		$Ambient = new Ambient();
		$map['type'] = $type;
		$map['is_show'] = $Ambient::IS_SHOW_TRUE;
		$res['ambient'] = $Ambient->getAmbient($map);
		return $res;
	}

	/**
	 * 新闻列表
	 * created by:Mp_Lxj
	 * @date:2019/1/10 22:13
	 * @param $type
	 */
	public function news($type)
	{
		$param = Request::instance()->param();
		$newsClass = new NewClass();
		$class_map['type'] = $type;
		$class_map['is_show'] = $newsClass::IS_SHOW_TRUE;
		$res['class'] = $newsClass->classList($class_map);

		$field = ['t.id','t.icon','t.title','t.brief','t.time','t.people','t1.name'];
		//新闻列表
		$Article = new Article();
		if($type == 1){
			$map['t.type'] = $Article::TYPE_NEW;
		}else{
			$map['t.type'] = $Article::TYPE_ACTIVITY;
		}
		if($param['class_id']){
			$map['t.new_id'] = $param['class_id'];
		}
		$map['t.is_show'] = $Article::IS_SHOW_TRUE;
		$map['t1.is_show'] = $newsClass::IS_SHOW_TRUE;
		$res['article'] = $Article->getNewList($map,$field,$param);

		$res['count'] = $Article->getNewCount($map);
		return $res;
	}

	/**
	 * 获取文章详情
	 * created by:Mp_Lxj
	 * @date:2019/1/10 23:04
	 * @param $type
	 * @return array|false|mixed|\PDOStatement|string|\think\Model
	 */
	public function article($type)
	{
		//banner图
		$Banner = new Banner();
		if($type == 'paymode'){
			$banner_map['mode'] = '付款方式';
		}elseif($type == 'route'){
			$banner_map['mode'] = '来校路线';
		}else{
			$banner_map['mode'] = '开班时间';
		}
		$res['banner'] = $Banner->getBannerFirst($banner_map);

		$Article = new Article();
		$map['type'] = $type;
		$map['is_show'] = $Article::IS_SHOW_TRUE;
		$res['article'] = $Article->getArticleDetail($map);
		return $res;
	}
}