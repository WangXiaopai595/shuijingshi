<?php
/**
 * Created by PhpStorm.
 * Class: 54714
 * Date: 2018/12/20
 * Time: 16:02
 */

namespace app\model;


use think\Db;
use think\Model;

class CourseControl extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	public $tableName = 'course_control';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 控件内容列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function courseList($param)
	{
		$map = [];
		if($param['class_id']){
			$map['class_id'] = $param['class_id'];
		}
		if($param['control'] == 2){
			$map['type'] = 1;
		}else if($param['control'] == 3){
			$map['type'] = 2;
		}else{
			return [];
		}
		$result['list'] = $this->commonModel->where($map)->order('sort,id desc')->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增控件内容
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
	 * 删除控件内容
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
	 * 修改控件内容
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
	 * 获取控件内容
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getCourseDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 获取长度
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 16:04
	 * @param array $map
	 * @return int|string
	 */
	public function getCount($map = [])
	{
		return $this->commonModel->where($map)->count();
	}

	/**
	 * 自定义获取列表
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getControl($map,$limit = '0,10',$field = '*',$order = 'sort,id desc')
	{
		return $this->commonModel->where($map)->limit($limit)->field($field)->order($order)->select();
	}

	/**
	 * 获取所有列表
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getControlAll($map,$field = '*',$order = 'sort,id desc')
	{
		return $this->commonModel->where($map)->field($field)->order($order)->select();
	}
}