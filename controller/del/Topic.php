<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
class Topic extends Common
{
	
	public function add()
	{

		return view('add');
	}
	public function alist()
	{

		$list = db('topic')->paginate(1);

		$this->assign('result',$list);
		return view('alist');
	
	}
	public function insert()
	{
		
		

		$title = input('post.title');
		$introduction = input('post.introduction');
		$ordernum = input('post.ordernum');
		$img_src = input('post.img');
		
		// $rule = [
		// 	'title' => 'require|min:2',
		// 	'urllink' => 'require',
		// 	'ordernum'  => 'require',
			
		// ];

		// $msg = [
		// 	'title.require' => '标题必须填写',
		// 	'title.length'  => '标题长度必须大于3位',
		// 	'urllink.require'=>'链接必须填写',
		// 	'ordernum.require'=>'序号必须填写',
			
		// ];

		// $data = [
		// 	'title'   => $title,
		// 	'urllink'=> $urllink,
		// 	'ordernum'    => $ordernum,
		// 	'img_src' => $img_src
		// ];
	
		// $validate = new Validate($rule,$msg);
		// $result   = $validate->check($data);
		// if(!$result){
		// 	$this->error($validate->getError());
		// 	die;
		// }
		
		// //插入数据
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('img');

		// 移动到框架应用根目录/public/uploads/ 目录下
		if($file){
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if($info){
				// 成功上传后 获取上传信息
				$img_src = "/uploads/{$info->getSaveName()}";
			   

			}else{
				// 上传失败获取错误信息
				$this->error($file->getError());
			}
		}
		
			$data = [
				'title'   => $title,
				'intd' => $introduction,
				'ordernum'=> $ordernum,
				'img_src' => $img_src
			];
			$ret = db('topic')->insert($data);
			if( $ret == false ){
				$this->error('添加失败');
			}
			$this->success('添加成功');

	}
	public function upimg()
	{
	// 	// 获取表单上传文件 例如上传了001.jpg
	// 	$file = request()->file('image');

	// 	// 移动到框架应用根目录/public/uploads/ 目录下
	// 	if($file){
	// 		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	// 		if($info){
	// 			// 成功上传后 获取上传信息
	// 			$img_src = "/uploads/{$info->getSaveName()}";
	// 			echo $img_src;

	// 		}else{
	// 			// 上传失败获取错误信息
	// 			$this->error($file->getError());
	// 		}
	// 	}
	}
}
