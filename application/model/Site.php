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

class Site extends Model
{
	public $tableName = 'site';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 获取站点信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getSiteDetail()
	{
		$map['name'] = '站点';
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 更新站点信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:45
	 * @param $data
	 */
	public function updateSite($data)
	{
		$site = $this->getSiteDetail();
		$site = $site['content'] ? unserialize($site['content']) : [];

		$file = Request::instance()->file();
		$path = uploadFile($file);
		if($path){
			foreach($path as $k=>$v){
				$data[$k] = $v;
			}
		}

		foreach($site as $key=>$value){
			foreach($data as $k=>$v){
				if(!isset($data[$key])){
					$data[$key] = $value;
				}
			}
		}

		$ctn = serialize($data);

		$map['name'] = '站点';
		$count = $this->commonModel->where($map)->count();

		if(!$count){
			$arr = [
				'name' => '站点',
				'content' => $ctn
			];
			$this->commonModel->insert($arr);
		}else{
			$this->commonModel->where($map)->update(['content' => $ctn]);
		}
	}
}