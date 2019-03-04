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

class VideoList extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	public $tableName = 'video_list';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 视频列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $param
	 * @return array
	 */
	public function videoList($param)
	{
		$map['type'] = ['=',$param['type']];
		if($param['title']){
			$map['title'] = ['like','%' . $param['title'] . '%'];
		}
		unset($param['type']);
		$result['list'] = $this->commonModel->where($map)->order('is_recommend desc,time desc,id desc')->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 视频列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $param
	 * @return array
	 */
	public function getVideoList($map)
	{
		$result = $this->commonModel->where($map)->order('id desc')->select();
		return $result;
	}

	/**
	 * 新增视频
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function videoInsert($data)
	{
		$data['created_at'] = time();
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除视频内容
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delVideo($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改视频
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editVideo($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取视频内容
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getVideoDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 获取长度
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:53
	 * @param array $map
	 * @return int|string
	 */
	public function getCount($map = [])
	{
		return $this->commonModel->where($map)->count();
	}

	/**
	 * 自定义获取商品
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getVideo($map,$limit = '0,10',$field = '*',$order = 'is_recommend desc,sort,time desc,id desc')
	{
		return $this->commonModel->where($map)->limit($limit)->field($field)->order($order)->select();
	}

	/**
	 * 自定义分页采访
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getVideoPage($map,$field = '*',$order = 'is_recommend desc,sort,time desc,id desc',$paginate = 9)
	{
		$result['list'] = $this->commonModel->where($map)->field($field)->order($order)->paginate($paginate);
		$result['page'] = $result['list']->render();
		return $result;
	}
}