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

class Curriculum extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示
	public $tableName = 'class';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 课程列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function classList($param)
	{
		$map = [];
		if($param['type_id']){
			$map['type_id'] = ['=',$param['type_id']];
		}
		if($param['child_id']){
			$map['child_id'] = ['=',$param['child_id']];
		}
		if($param['title']){
			$map['title'] = ['like','%'. $param['title'] .'%'];
		}
		if($param['teacher']){
			$map['teacher'] = ['like','%'. $param['teacher'] .'%'];
		}
		$field = ['t.*','t1.name','t2.name as child_name'];
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__CLASS_TYPE__ t1','t.type_id=t1.id','LEFT')
			->join('__CLASS_TYPE__ t2','t.child_id=t2.id','LEFT')
			->where($map)
			->field($field)
			->order('t.time desc,t.id desc')
			->paginate(10,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增课程
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function classInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除课程
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delClass($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改课程
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editClass($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取课程信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getClassDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 课程列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function getClassList($map,$param)
	{
		$field = ['t.*'];
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__CLASS_TYPE__ t1','t.type_id=t1.id','LEFT')
			->join('__CLASS_TYPE__ t2','t.child_id=t2.id','LEFT')
			->where($map)
			->field($field)
			->order('t.time desc,t.id desc')
			->paginate(8,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 获取数量
	 * created by:Mp_Lxj
	 * @date:2019/1/8 21:43
	 * @param $map
	 * @return int|string
	 */
	public function getCount($map)
	{
		return $this->commonModel
			->alias('t')
			->join('__CLASS_TYPE__ t1','t.type_id=t1.id','LEFT')
			->join('__CLASS_TYPE__ t2','t.child_id=t2.id','LEFT')
			->where($map)
			->count();
	}

	/**
	 * 增加浏览量
	 * Created by：Mp_Lxj
	 * @date 2019/1/9 15:11
	 * @param $map
	 * @param int $num
	 * @return int|true
	 * @throws \think\Exception
	 */
	public function peopleInc($map,$num = 1)
	{
		return $this->commonModel->where($map)->setInc('people',$num);
	}
}