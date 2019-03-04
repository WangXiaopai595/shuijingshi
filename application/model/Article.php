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

class Article extends Model
{
	const IS_SHOW_TRUE = 1;//显示
	const IS_SHOW_FALSE = 2;//不显示

	//类型
	const TYPE_NEW = 'new';//新闻
	const TYPE_STUDENT = 'student';//明星学员
	const TYPE_COMPANY = 'company';//合作企业
	const TYPE_INTRODUCE = 'introduce';//公司介绍
	const TYPE_CLASS_TIME = 'classtime';//开班时间
	const TYPE_ROUTE = 'route';//来校路线
	const TYPE_ACTIVITY = 'activity';//校园活动
	const TYPE_HONOR = 'honor';//学院荣誉
	const TYPE_MEDIA = 'media';//媒体采访
	const TYPE_RECORD = 'record';//学生战绩
	const TYPE_GRANT = 'grant';//授权认证
	const TYPE_RECRUIT = 'recruit';//招聘会回顾
	const TYPE_CG = 'cg';//CG交流活动

	public $tableName = 'article';

	public function __construct()
	{
		parent::__construct();
		$this->commonModel = Db::name($this->tableName);
	}

	/**
	 * 文章列表
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 16:21
	 * @param $param
	 * @return array
	 */
	public function articleList($param)
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
	 * 新闻列表查询
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:48
	 * @param $param
	 * @return mixed
	 */
	public function getArticleList($param)
	{
		$map['t.type'] = ['=',$param['type']];
		if($param['title']){
			$map['title'] = ['like','%' . $param['title'] . '%'];
		}
		if($param['new_id']){
			$map['new_id'] = ['=',$param['new_id']];
		}
		unset($param['type']);
		$field = ['t.*','t1.name'];
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__NEW_CLASS__ t1','t.new_id=t1.id','LEFT')
			->where($map)
			->order('is_recommend desc,time desc,id desc')
			->field($field)
			->paginate(15,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新增文章
	 * Created by：Mp_Lxj
	 * @date 2018/12/21 17:04
	 * @param $data
	 * @return string
	 */
	public function articleInsert($data)
	{
		$data['created_at'] = time();
		$this->commonModel->insert($data);
		return $this->commonModel->getLastInsID();
	}

	/**
	 * 删除文章内容
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:47
	 * @param $map
	 * @return int
	 */
	public function delArticle($map)
	{
		return $this->commonModel->where($map)->delete();
	}

	/**
	 * 修改文章
	 * created by:Mp_Lxj
	 * @date:2018/12/21 18:51
	 * @param $map
	 * @param $data
	 * @return int|string
	 */
	public function editArticle($map,$data)
	{
		return $this->commonModel->where($map)->update($data);
	}

	/**
	 * 获取文章内容
	 * Created by：Mp_Lxj
	 * @date 2018/12/20 16:04
	 * @return array|false|mixed|\PDOStatement|string|Model
	 */
	public function getArticleDetail($map)
	{
		return $this->commonModel->where($map)->find();
	}

	/**
	 * 获取数量
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:16
	 * @param array $map
	 * @return int|string
	 */
	public function getCount($map = [])
	{
		return $this->commonModel->where($map)->count();
	}

	/**
	 * 自定义获取文章
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getArticle($map,$limit = '0,10',$field = '*',$order = 'is_recommend desc,time desc,id desc')
	{
		return $this->commonModel->where($map)->limit($limit)->field($field)->order($order)->select();
	}

	/**
	 * 自定义获取耽搁文章
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getArticleFirst($map,$field = '*',$order = 'is_recommend desc,time desc,id desc')
	{
		return $this->commonModel->where($map)->field($field)->order($order)->find();
	}

	/**
	 * 增加浏览量
	 * Created by：Mp_Lxj
	 * @date 2019/1/9 15:11
	 * @param $map
	 * @param int $num
	 * @return int|true
	 * @throws \think\Exception
	 */
	public function peopleInc($map,$num = 1)
	{
		return $this->commonModel->where($map)->setInc('people',$num);
	}

	/**
	 * 自定义分页文章
	 * Created by：Mp_Lxj
	 * @date 2019/1/8 9:18
	 * @param $map
	 * @param string $limit
	 * @param string $field
	 * @param string $order
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getArticlePage($map,$field = '*',$order = 'is_recommend desc,time desc,id desc')
	{
		$result['list'] = $this->commonModel->where($map)->field($field)->order($order)->paginate(8);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 新闻列表查询
	 * Created by：Mp_Lxj
	 * @date 2018/12/26 13:48
	 * @param $param
	 * @return mixed
	 */
	public function getNewList($map,$field,$param)
	{
		$result['list'] = $this->commonModel
			->alias('t')
			->join('__NEW_CLASS__ t1','t.new_id=t1.id','LEFT')
			->where($map)
			->order('is_recommend desc,time desc,id desc')
			->field($field)
			->paginate(10,false,$param);
		$result['page'] = $result['list']->render();
		return $result;
	}

	/**
	 * 获取新闻条数
	 * created by:Mp_Lxj
	 * @date:2019/1/10 22:33
	 * @param $map
	 * @return int|string
	 */
	public function getNewCount($map)
	{
		return $this->commonModel
			->alias('t')
			->join('__NEW_CLASS__ t1','t.new_id=t1.id','LEFT')
			->where($map)
			->count();
	}
}