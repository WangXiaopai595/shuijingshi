<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class RoleAdmin extends Model
{
	public $tableName = 'role_admin';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 添加数据
	 * @param $data 接收到的添加账号信息
	 * @return array 返回添加是否成功状态值
	 */
	public function adminRoleAdd($data){
		$res = $this->commonModel->insert($data);
		if($res){
			\think\Cache::rm('node');
			\think\Cache::rm('menu');
			$result = true;
		}else{
			$result = false;
		}
		return $result;
	}

	/**
	 * 单条查询
	 * @param $data 接收的参数
	 * @return array|false|\PDOStatement|string|Model 返回查询结果
	 */
	public function dataSingle($map,$field){
		$result = $this->commonModel->where($map)->field($field)->find();
		return $result;
	}

	/**
	 * 删除
	 * @param $map 传入的id   条件
	 * @return array 返回删除状态信息
	 * @throws \think\Exception
	 */
	public function adminRoleDelete($map){
		$res = $this->commonModel->where($map)->delete();
		if($res){
			\think\Cache::rm('node');
			\think\Cache::rm('menu');
			$result = true;
		}else{
			$result = false;
		}
		return $result;
	}

	/**
	 * 数据更新
	 * @param $data 接收的参数
	 * @return array 返回数据更新状态
	 */
	public function adminRoleEdit($map,$data){
		$res = $this->commonModel->where($map)->update($data);
		if($res){
			$result = array('msg'=>'修改成功','status'=>1);
		}else{
			$result = array('msg'=>'什么也没改','status'=>0);
		}
		return $result;
	}

	/**
	 * 单条查询
	 * @param $data 接收的参数
	 * @return array|false|\PDOStatement|string|Model 返回查询结果
	 */
	public function dataFind($map,$field){
		$result = $this
			->commonModel
			->alias('t')
			->join('__ROLE__ t1','t.role_id=t1.id','left')
			->where($map)
			->field($field)
			->find();
		return $result;
	}
}