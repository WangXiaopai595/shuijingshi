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

class Cases extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示
	public $tableName = 'case';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 案例列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function caseList($param)
	{
		$map = [];
		if($param['title']){
			$map['title'] = ['like','%' . $param['title'] . '%'];
		}
		$result = $this->commonModel->where($map)->order('sort,id desc')->select();
		return $result;
	}

	/**
	 * 新增
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function caseInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delCase($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改案例
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editCase($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取案例信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getCaseDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 案例列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function getCaseList($map)
	{
		$result = $this->commonModel->where($map)->order('sort,id desc')->select();
		return $result;
	}
}