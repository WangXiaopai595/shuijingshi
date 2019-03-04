<?php
namespace app\admin\controller;
use app\model\Site;
use think\Controller;
class Login extends Controller
{
	public function __construct()
	{
		parent::__construct();
		if(\think\Session::has('member')){
			$this->redirect('index/index');
		}
	}

	/**
	 * 登陆页
	 * @return mixed
	 */
	public function index(){

		$site = new \app\model\Site();
		$data = $site->getSiteDetail();
		$data['content'] = $data['content'] ? unserialize($data['content']) : [];
		$this->assign('site',$data);

		return $this->fetch();
	}

	/**
	 * 验证码
	 */
	public function code(){
		$config = array(
			'length'=>4,
			'useCurve'  =>  false,
		);
		$captcha = new \think\captcha\Captcha($config);
		return $captcha->entry();
	}

	/**
	 * 用户登陆
	 * @return array
	 */
	public function login(){
		$data = \think\Request::instance()->param();
		//检测验证码
		if(check_verify($data['code'])){
			unset($data['code']);
			$data['password'] = md5($data['password']);

			//查询当前用户基本信息
			$map['user'] = $data['user'];
			$field = [
				'id',//id
				'user',//用户名
				'password',//密码
				'status',//状态
				'remark',//备注
				'realname',//身份
				'phone',//手机
				'email'//邮箱
			];
			$user = \think\Loader::model('Admin')->dataSingle($map,$field);

			//是否被禁用
			if($user['status'] == 0){
				$result = ['status'=>0,'msg'=>'该账号已被禁用'];
			}else{
				//验证密码是否正确
				if($data['password'] == $user['password']){
					$data['last_login_ip'] = $_SERVER["REMOTE_ADDR"];
					$data['last_login_time'] = time();
					\think\Loader::model('Admin')->userEdit($map,$data);
					\think\Session::set('member',$user);
					$result = ['status'=>1,'msg'=>'登录成功'];
				}else{
					$result = ['status'=>0,'msg'=>'用户名或密码错误'];
				}
			}
		}else{
			$result = ['status'=>0,'msg'=>'验证码错误'];
		}

		return $result;
	}
}