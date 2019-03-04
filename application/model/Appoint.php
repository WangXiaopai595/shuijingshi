<?php
/**
 * Created by PhpStorm.
 * Apponit: 54714
 * Date: 2018/12/20
 * Time: 16:02
 */

namespace app\model;


use think\Db;
use think\Model;
use think\Request;

class Appoint extends Model
{
	public $tableName = 'appoint';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 预约列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function appointList($param)
	{
		$map = [];
		if($param['phone']){
			$map['t.phone'] = ['=',$param['phone']];
		}
		if($param['name']){
			$map['t.name'] = ['like','%' . $param['name'] . '%'];
		}
		if($param['uid']){
			$map['t.uid'] = ['=',$param['uid']];
		}
		if($param['username']){
			$map['t1.name'] = ['like','%' . $param['username'] . '%'];
		}
		$field = ['t.*','t1.name as user_name'];
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__USER__ t1','t.uid=t1.id','LEFT')
			->where($map)
			->order('t.time desc,t.id desc')
			->field($field)
			->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增预约
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function appointInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除预约
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delApponit($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改预约
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editApponit($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取预约
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getApponitDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	public function getCount($map = [])
	{
		return $this->commonModel->where($map)->count();
	}
}