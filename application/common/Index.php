<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2019/1/8
 * Time: 9:08
 */

namespace app\common;


use app\model\Appoint;
use app\model\Article;
use app\model\Banner;
use app\model\CourseClass;
use app\model\CourseControl;
use app\model\CourseDetail;
use app\model\Teacher;
use app\model\VideoList;
use app\model\Work;
use think\Request;

class Index
{
	/**
	 * 首页数据
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 14:10
	 * @return mixed
	 */
	public function index($is_mobile = false)
	{
		//获取banner
		$Banner = new Banner();
		$banner_map['mode'] = '首页';
		$banner_map['is_show'] =
		$res['banner'] = $Banner->getBanner($banner_map);

		//课程二级菜单
		$Course = new CourseClass();
		$course_map['is_show'] = $Course::IS_SHOW_TRUE;
		$res['course'] = $Course->classList($course_map,['id','name','icon']);

		if($is_mobile){
			//学员采访
			$Video = new VideoList();
			$video_map['type'] = 1;
			$video_map['is_show'] = $Video::IS_SHOW_TRUE;
			$res['video'] = $Video->getVideo($video_map,'0,1');

			//学员作品
			$Work = new Work();
			$work_map['type'] = 2;
			$work_map['is_show'] = $Work::IS_SHOW_TRUE;
			$res['work'] = $Work->getWork($work_map,'0,2');

			//新闻
			$News = new Article();
			$new_map['type'] = 'new';
			$new_map['is_show'] = $News::IS_SHOW_TRUE;
			$res['news'] = $News->getArticle($new_map,'0,3',['id','title','icon','brief','time']);

			//学院荣誉
			$News = new Article();
			$new_map['type'] = 'honor';
			$new_map['is_show'] = $News::IS_SHOW_TRUE;
			$res['honor'] = $News->getArticle($new_map,'0,6',['id','title','icon','brief','time']);
		}else{
			//学员采访
			$Video = new VideoList();
			$video_map['type'] = 1;
			$video_map['is_show'] = $Video::IS_SHOW_TRUE;
			$res['video'] = $Video->getVideo($video_map,'0,3');

			//学员作品
			$Work = new Work();
			$work_map['type'] = 2;
			$work_map['is_show'] = $Work::IS_SHOW_TRUE;
			$res['work'] = $Work->getWork($work_map,'0,3');

			//师资
			$Teacher = new Teacher();
			$teacher_map['is_show'] = $Teacher::IS_SHOW_TRUE;
			$res['teacher'] = $Teacher->getTeacher($teacher_map,'0,4');

			//新闻
			$News = new Article();
			$new_map['type'] = 'new';
			$new_map['is_show'] = $News::IS_SHOW_TRUE;
			$res['news'] = $News->getArticle($new_map,'0,4',['id','title','icon','brief','time']);
		}

		return $res;
	}

	/**
	 * 精品课程详情
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 16:04
	 * @param bool $is_mobile
	 * @return mixed
	 */
	public function course($is_mobile = false)
	{
		$param = Request::instance()->param();
		$Course = new CourseClass();
		$map['id'] = $param['course'];
		$res['course'] = $Course->getClassDetail($map);

		$CourseDetail = new CourseDetail();
		$detail_map['class_id'] = $param['course'];
		if($is_mobile){
			$detail_map['type'] = $CourseDetail::MOBILE;
		}else{
			$detail_map['type'] = $CourseDetail::PC;
		}
		$detail_map['is_show'] = $CourseDetail::IS_SHOW_TRUE;
		$res['detail'] = $CourseDetail->getCourse($detail_map);

		if($res['course']['control'] == 2 || $res['course']['control'] == 3){
			$CourseControl = new CourseControl();
			$control_map['class_id'] = $param['course'];
			if($res['course']['control'] == 2){
				$control_map['type'] = 1;
				$res['control'] = $CourseControl->getControlAll($control_map);
			}else{
				$control_map['type'] = 2;
				$res['control'] = $CourseControl->getControl($control_map,'0,4');
			}
		}else{
			$res['control'] = [];
		}

		if(count($res['detail']) < $res['course']['control_sort']){
			$res['detail'][count($res['detail']) - 1]['control'] = $res['control'];
		}else{
			$index = $res['course']['control_sort'] > 0 ? $res['course']['control_sort'] - 1 : 0;
			$res['detail'][$index]['control'] = $res['control'];
		}
		unset($res['control']);

		return $res;
	}

	/**
	 * 在线预约
	 * created by:Mp_Lxj
	 * @date:2019/1/8 20:27
	 * @return array|\Illuminate\Http\JsonResponse
	 */
	public function appoint()
	{
		$param = Request::instance()->param();
		$param['time'] = time();
		$user = session('user');
		$param['uid'] = $user['id'];

		$Appoint = new Appoint();
		$res = $Appoint->appointInsert($param);
		if($res){
			return trueAjax('预约成功');
		}else{
			return falseAjax('预约失败');
		}
	}
}