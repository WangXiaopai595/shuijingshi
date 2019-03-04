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

class Story extends Model
{
	public $tableName = 'story';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 成长历程列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function storyList()
	{
		$result = $this->commonModel->order('time desc,id')->select();
		return $result;
	}

	/**
	 * 新增成长历程
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function storyInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除成长历程
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delStory($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改成长历程
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editStory($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取成长历程
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getStoryDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}
}