<?php
namespace app\admin\controller;

class Common extends \think\Controller
{
     public function _initialize()
    {
        
        //判断是否登录
        if(session('admin_id')==NULL)
        {
        	$this->error("请登录！",'admin/login/login');
        }
        if( time() - session('logintime') > 5*60 )
        {
        	$this->error("登录超时",'admin/login/login');
        }
        session('logintime',time());
    }
    
    
}

