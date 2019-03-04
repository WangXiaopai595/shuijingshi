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

class ClassCollection extends Model
{
	public $tableName = 'class_collection';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 获取数量
	 * Created by：Mp_Lxj
	 * @date 2019/1/9 15:14
	 * @param $map
	 * @return int|string
	 */
	public function getCount($map)
	{
		return $this->commonModel->where($map)->count();
	}

	/**
	 * 收藏
	 * Created by：Mp_Lxj
	 * @date 2019/1/9 15:34
	 * @param $data
	 * @return string
	 */
	public function insertCollect($data)
	{
		$data['time'] = time();
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除收藏
	 * Created by：Mp_Lxj
	 * @date 2019/1/9 15:35
	 * @param $map
	 * @return int
	 * @throws \think\Exception
	 */
	public function delCollect($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	public function getCollectionList($map)
	{
		$field = ['t1.*'];
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__CLASS__ t1','t.class_id=t1.id','LEFT')
			->where($map)
			->order('t.time desc')
			->field($field)
			->paginate(6);
		$result['page'] = $result['list']->render();
		return $result;
	}

	public function getCollectionCount($map)
	{
		return $this->commonModel
			->alias('t')
			->join('__CLASS__ t1','t.class_id=t1.id','LEFT')
			->where($map)
			->count();
	}
}