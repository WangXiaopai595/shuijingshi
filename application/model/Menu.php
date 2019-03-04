<?php
/**
 * Created by PhpStorm.
 * menu: 54714
 * Date: 2018/12/20
 * Time: 16:02
 */

namespace app\model;


use think\Db;
use think\Model;

class Menu extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	public $tableName = 'menu';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 菜单列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function menuList($map = [])
	{
		$result = $this->commonModel->where($map)->order('sort,id')->select();
		return $result;
	}

	/**
	 * 新增菜单
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function menuInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除菜单
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delMenu($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改菜单
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editMenu($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取菜单信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getMenuDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}
}