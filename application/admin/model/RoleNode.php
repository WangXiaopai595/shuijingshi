<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class RoleNode extends Model
{
	public $tableName = 'role_node';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 列表
	 * @return mixed 列表信息  分页标签类
	 */
	public function roleList($map,$field){
		$result = $this->commonModel->where($map)->field($field)->select();
		return $result;
	}

	/**
	 * 删除
	 * @param $map 传入的id   条件
	 * @return array 返回删除状态信息
	 * @throws \think\Exception
	 */
	public function roleDelete($map){
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
	 * 添加、插入数据  多条
	 * @param $data要添加的数据
	 */
	public function AddAll($data){
		$res = $this->commonModel->insertAll($data);
		if($res){
			\think\Cache::rm('node');
			\think\Cache::rm('menu');
			$result = true;
		}else{
			$result = false;
		}
		return $result;
	}
}