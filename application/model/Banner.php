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

class Banner extends Model
{
	public $tableName = 'banner';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * banner列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $map
	 * @return mixed
	 */
	public function bannerList()
	{
		$param = Request::instance()->param();
		$map = [];
		if($param['mode']){
			$map['mode'] = $param['mode'];
		}
		$result['list'] = $this->commonModel->where($map)->order('sort,id desc')->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 获取banner
	 * Created by：Mp_Lxj
	 * @date 2019/1/9 16:44
	 * @param $map
	 * @return array|false|\PDOStatement|string|Model
	 */
	public function getBannerFirst($map)
	{
		return $this->commonModel->where($map)->order('sort,id desc')->find();
	}

	/**
	 * 获取banner信息
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getBannerDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 新增banner
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function bannerInsert($data)
	{
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除banner
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delBanner($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改bnner基本信息
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editBanner($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取banner
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:10
	 * @param $map
	 * @param $field
	 * @return mixed
	 */
	public function getBanner($map,$field = '*')
	{
		return $this->commonModel->where($map)->field($field)->select();
	}
}