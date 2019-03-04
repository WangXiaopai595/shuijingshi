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

class Work extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	public $tableName = 'work';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 作品列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function workList($param)
	{
		$map = [];
		if($param['class_id']){
			$map['class_id'] = ['=',$param['class_id']];
		}
		if($param['title']){
			$map['title'] = ['like','%'. $param['title'] .'%'];
		}
		$field = ['t.*','t1.name'];
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__WORK_CLASS__ t1','t.class_id=t1.id','LEFT')
			->where($map)
			->field($field)
			->order('t.time desc,t.id desc')
			->paginate(10,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增作品
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function workInsert($data)
	{
		$data['created_at'] = time();
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除作品
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delWork($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改作品
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editWork($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取作品信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getWorkDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 获取长度
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 10:04
	 * @param array $map
	 * @return int|string
	 */
	public function getCount($map = [])
	{
		return $this->commonModel->where($map)->count();
	}

	/**
	 * 自定义获取作品
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getWork($map,$limit = '0,10',$field = '*',$order = 'time desc,id desc')
	{
		return $this->commonModel->where($map)->limit($limit)->field($field)->order($order)->select();
	}

	/**
	 * 作品列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function getWorkList($map,$param)
	{
		$field = ['t.*'];
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__WORK_CLASS__ t1','t.class_id=t1.id','LEFT')
			->where($map)
			->field($field)
			->order('t.time desc,t.id desc')
			->paginate(8,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 作品总数
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function getWorkCount($map)
	{
		return $this->commonModel
			->alias('t')
			->join('__WORK_CLASS__ t1','t.class_id=t1.id','LEFT')
			->where($map)
			->count();
	}
}