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

class Teacher extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	public $tableName = 'teacher';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 教师列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function teacherList($param)
	{
		$map = [];
		if($param['name']){
			$map['name'] = ['like','%' . $param['name'] . '%'];
		}
		if($param['type']){
			$map['type'] = ['=',$param['type']];
		}
		unset($param['type']);
		$result['list'] = $this->commonModel->where($map)->order('sort,time desc,id desc')->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增教师
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function teacherInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除教师
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delTeacher($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改教师
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function ediTteacher($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取教师信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getTeacherDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 自定义获取师资列表
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getTeacher($map,$limit = '0,10',$field = '*',$order = 'sort,time desc,id desc')
	{
		return $this->commonModel->where($map)->limit($limit)->field($field)->order($order)->select();
	}

	/**
	 * 自定义获取师资列表
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getTeacherAll($map,$field = '*',$order = 'sort,time desc,id desc')
	{
		return $this->commonModel->where($map)->field($field)->order($order)->select();
	}
}