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
use think\Request;

class ClassList extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	public $tableName = 'class_list';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 课程内容列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function classList($param)
	{
		$map['type'] = ['=',$param['type']];
		if($param['class_id']){
			$map['class_id'] = ['=',$param['class_id']];
		}
		if($param['title']){
			$map['title'] = ['like','%' . $param['title'] . '%'];
		}
		unset($param['type']);
		$result['list'] = $this->commonModel->where($map)->order('sort,id desc')->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增课程内容
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
	 * 删除课程内容
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
	 * 修改课程内容
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
	 * 获取课程内容
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getClassDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 课程内容列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function getClassList($map)
	{
		return $this->commonModel->where($map)->order('sort,id')->select();
	}
}