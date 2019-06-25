<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
class Banner extends Common
{
	
	public function add()
	{
		return view('add');
	}
	public function alist()
	{
		$list = db('goods')->paginate(1);

		$this->assign('result',$list);
		return view('alist');
	}
	public function insert()
	{		

		$name = input('post.name');
		$price = input('post.price');
		$stock = input('post.stock');
		$sales = input('post.sales');
		$reviews = input('post.reviews');
		$collect = input('post.collect');
		$img_src = input('post.img');
		
		$rule = [
			'name' => 'require|min:2',
			'price' => 'require',
			'stock'  => 'require',
			
		];

		$msg = [
			'name.require' => '商品名称必须填写',
			'name.length'  => '商品名称长度必须大于3位',
			'price.require'=>'价格必须填写',
			'stock.require'=>'库存必须填写',
			
		];

		$data = [
			'name'   => $name,
			'price'=> $price,
			'stock'    => $stock,
			'sales'    => $sales,
			'reviews'    => $reviews,
			'collect'    => $collect,
			'img_src' => $img_src
		];
	
		$validate = new Validate($rule,$msg);
		$result   = $validate->check($data);
		if(!$result){
			$this->error($validate->getError());
			die;
		}
		
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
		var_dump($img_src);
			$data = [
				'name'   => $name,
				'price'=> $price,
				'stock'    => $stock,
				'sales'    => $sales,
				'reviews'    => $reviews,
				'collect'    => $collect,
				'img_src' => $img_src
			];
			$ret = db('goods')->insert($data);
			if( $ret == false ){
				$this->error('添加失败');
			}
			$this->success('添加成功');

	}
	public function upimg()
	{
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('image');

		// 移动到框架应用根目录/public/uploads/ 目录下
		if($file){
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if($info){
				// 成功上传后 获取上传信息
				$img_src = "/uploads/{$info->getSaveName()}";
				echo $img_src;

			}else{
				// 上传失败获取错误信息
				$this->error($file->getError());
			}
		}
	}

	public function del()
	{
		$id = input('get.id');
		if( $id != ""){
			$ret = db('goods')->where("id={$id}")->delete();
			if( $ret == false ){
	    		$this->error('删除失败');
	    	}
	    	$this->success('删除成功');
		}

	}
	public function edit()
	{
		$id = input('get.id');
		$result =[];
		if( $id != ""){
			$result = db('goods')->where("id={$id}")->find();
		}
		$this->assign('result',$result);
		return view('edit');
	}

	public function update()
	{
		$name = input('post.name','','strip_tags');
		$id = input('post.id');
		$price = input('post.price');
		$stock = input('post.stock');
		$sales = input('post.sales');
		$reviews = input('post.reviews');
		$collect = input('post.collect');
		$img_src = input('post.img_src');
    	
    	$rule = [
		
		  	'name' => 'require|min:2',
			'price' => 'require',
			'stock'  => 'require',
		 
		];

		$msg = [
		    'name.require' => '商品名称必须填写',
			'name.length'  => '名称长度必须大于3位',
			'price.require'=>'价格必须填写',
			'stock.require'=>'库存必须填写',
		];

		$data = [
		    'name'   => $name,
			'price'=> $price,
			'stock'    => $stock,
			'sales'    => $sales,
			'reviews'    => $reviews,
			'collect'    => $collect,
			'img_src' => $img_src
		];

		$update = [
			'name'   => $name,
			'price'=> $price,
			'stock'    => $stock,
		];
		
		$validate = new Validate($rule,$msg);
		$result   = $validate->check($data);
		if(!$result){
			$this->error($validate->getError());
			die;
		}
		// 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('img');

	    // 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	            // 成功上传后 获取上传信息
	        	$img_src = "/uploads/{$info->getSaveName()}";
	           $update['img_src'] = $img_src;

	        }else{
	            // 上传失败获取错误信息
	            $this->error($file->getError());
	        }
	    }

	    $ret = db('goods')->where("id={$id}")->update($update);

	    if( $ret === false){
	    	$this->error("编辑错误");
	    }
	    if( $ret === 0){
	    	$this->error("没有任何修改");
	    }
	    $this->success("修改成功");
	}
}
