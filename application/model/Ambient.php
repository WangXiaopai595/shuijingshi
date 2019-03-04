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

class Ambient extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示
	public $tableName = 'ambient';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 环境列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $param
	 * @return array
	 */
	public function ambientList($map)
	{
		$result['list'] = $this->commonModel->where($map)->order('sort,time desc,id desc')->paginate(15);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增环境
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function ambientInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除环境内容
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delAmbient($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改环境
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editAmbient($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取环境内容
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getAmbientDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 环境列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $param
	 * @return array
	 */
	public function getAmbient($map)
	{
		return $this->commonModel->where($map)->order('sort,time desc,id desc')->select();
	}
}