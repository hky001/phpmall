<?php
namespace app\admin\controller;
use \think\Db;
class Login extends \think\Controller
{
    public function login()
    {
    	return view();
    }
    public function check()
    {
    	$account = input('post.account');
    	$pwd = input('post.pwd');
    	$pwd = md5($pwd);
    	$ret = db('admin')->where("account='{$account}' and pwd ='{$pwd}'")->find();
    	if( $ret == false ){
    		$this->error("账号密码不匹配");
    	}
    	session('head_img',$ret['head_img']);
    	session('admin_id',$ret['id']);
    	session('name',$ret['name']);
    	session('logintime',time());
    	$this->success("登录成功",'admin/index/index');
    }
    public function logout()
    {
        session('admin_id',null);

        session('head_img',null);

        session('name',null);

        $this->success("退出成功！",Url('admin/login/login'));
    }
}
