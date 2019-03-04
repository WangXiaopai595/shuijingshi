<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Node extends Model
{
	public $tableName = 'node';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 列表
	 * @return mixed 列表信息  分页标签类
	 */
	public function topList($map,$field){
		$result = $this->commonModel->where($map)->field($field)->order('sort,id')->select();
		return $result;
	}

	/**
	 * 添加数据
	 * @param $data 接收到的添加账号信息
	 * @return array 返回添加是否成功状态值
	 */
	public function nodeAdd($data){
		$res = $this->commonModel->insert($data);
		if($res){
			$result = ['status'=>1,'msg'=>'添加成功'];
		}else{
			$result = ['status'=>0,'msg'=>'参数错误'];
		}
		return $result;
	}

	/**
	 * 分页列表
	 * @return mixed 列表信息  分页标签类
	 */
	public function nodeList($field,$param){
		$map = [];
		if($param['name']){
			$map['t.name'] = ['like','%' . $param['name'] . '%'];
		}
		if($param['title']){
			$map['t.title'] = ['like','%' . $param['title'] . '%'];
		}
		$result['list'] = $this
			->commonModel
			->view('__NODE__ t',$field)
			->view('__NODE__ t1',['title'=>'ptitle','name'=>'pname'],'t.pid=t1.id','left')
			->where($map)
			->order('t.sort,t.id,t1.id')
			->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 数据更新
	 * @param $data 接收的参数
	 * @return array 返回数据更新状态
	 */
	public function nodeEdit($map,$data){
		$res = $this->commonModel->where($map)->update($data);
		if($res){
			\think\Cache::rm('node');
			\think\Cache::rm('menu');
			$result = array('msg'=>'修改成功','status'=>1);
		}else{
			$result = array('msg'=>'什么也没改','status'=>0);
		}
		return $result;
	}

	/**
	 * 删除
	 * @param $map 传入的id   条件
	 * @return array 返回删除状态信息
	 * @throws \think\Exception
	 */
	public function nodepDelete($map){
		$res = $this->commonModel->where($map)->delete();
		if($res){
			\think\Cache::rm('node');
			\think\Cache::rm('menu');
			$result = array('msg'=>'删除成功','status'=>1);
		}else{
			$result = array('msg'=>'参数错误','status'=>0);
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
	 * 分页列表
	 * @return mixed 列表信息  分页标签类
	 */
	public function lists($map,$field1,$field2){
		$result = $this
			->commonModel
			->view('__NODE__ t',$field1)
			->view('__NODE__ t1',$field2,'t.pid=t1.id','left')
			->order('t.sort,t.id,t1.id')
			->where($map)
			->select();
		return $result;
	}

	/**
	 * 分页列表
	 * @return mixed 列表信息  分页标签类
	 */
	public function adminNode($map,$field){
		$result = $this
			->commonModel
			->view('__NODE__ t',$field)
			->view('__NODE__ t1',['name'=>'pname'],'t.pid=t1.id','left')
			->where($map)
			->order('t.sort,t.id,t1.id')
			->select();
		return $result;
	}

}