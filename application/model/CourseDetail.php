<?php
/**
 * Created by PhpStorm.
 * User: 54714
 * Date: 2018/12/20
 * Time: 16:02
 */

namespace app\model;


use think\Db;
use think\Model;
use think\Request;

class CourseDetail extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	const PC = 1;//PC
	const MOBILE = 2;//wap

	public $tableName = 'course_detail';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 精品课程图片列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $param
	 * @return mixed
	 */
	public function courseList($param)
	{
		$map = [];
		if($param['class_id']){
			$map['class_id'] = $param['class_id'];
		}
		if($param['img_type']){
			$map['type'] = $param['img_type'];
		}
		$result['list'] = $this->commonModel->where($map)->order('sort,id')->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 获取精品课程图片信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getCourseDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 新增精品课程图片
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function courseInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除精品课程图片
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delCourse($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改精品课程图片基本信息
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editCourse($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 精品课程图片列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $param
	 * @return mixed
	 */
	public function getCourse($map)
	{
		return $this->commonModel->where($map)->order('sort,id')->select();
	}
}