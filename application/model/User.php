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

class User extends Model
{
	public $tableName = 'user';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 用户列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function userList($param)
	{
		$map = [];
		if($param['id']){
			$map['id'] = ['=',$param['id']];
		}
		if($param['phone']){
			$map['phone'] = ['=',$param['phone']];
		}
		if($param['name']){
			$map['name'] = ['like','%'. $param['name'] .'%'];
		}
		$result['list'] = $this->commonModel->where($map)->order('time')->paginate(10,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 获取用户信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getUserDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 新增用户
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function userInsert($data)
	{
		$data['time'] = time();
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除用户
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delUser($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改用户基本信息
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editUser($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取用户是否已存在
	 * created by:Mp_Lxj
	 * @date:2018/12/26 19:21
	 * @param $phone
	 * @return int|string
	 */
	public function getUserCount($phone)
	{
		$map['phone'] = $phone;
		return $this->commonModel->where($map)->count();
	}

	public function getCount($map = [])
	{
		return $this->commonModel->where($map)->count();
	}
}