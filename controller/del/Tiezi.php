<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
class Tiezi extends Common
{
	

	public function alist()
	{
		// $list = db('tiezi')->paginate(1);

		// $data = [];
		// foreach ($list as $val) {
		// 	$user_id = $val['user_id'];
		// 	//查用户信息，表：user
		// 	$info = db('user')->where("id={$user_id}")->find();

		// 	$val['img_src'] = json_decode($val['img_src'],true);
		// 	$val['tell'] = $info['tell'];
			
		// 	$data[] = $val; 
		// }
		// 
		

		// 连接查询
		$list = db('tiezi')->alias('a')->join('user b',"a.user_id=b.id")->join('topic c','a.topic_id=c.id')->field('a.*,b.tell,b.id as bid,c.title')->order('a.id desc')->paginate(10);

		$data = $list->all();
		// dump($data);die;
		$data = [];
		foreach ($list as $val) {
					
					$val['img_src'] = json_decode($val['img_src'],true);
					
					$data[] = $val; 
				}
		$this->assign('page',$list->render());
		$this->assign('result',$data);
		return view('alist');
	}


}
