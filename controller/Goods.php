<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
class Goods extends Common
{
	
	public function add()
	{
		/*一级分类*/
        
        $field = "division_id,name";
        $category = db('division')->where("level=1")->field($field)->select();
        $this->assign('category', $category);
        return $this->fetch();
		
	}
	public function getcategory()
    {

        $id = intval(input('post.id'));

        $field = "division_id,name";
        $categorylist = db('division')->where("level=2")->where("pid={$id}")->field($field)->select();
        
        exit(json_encode($categorylist));
    }
	public function details()
	{
		$list = db('goods')->paginate(10);

		$this->assign('result',$list);
		return view('details');
	}

	public function alist()
	{
		$list = db('goods')->paginate(10);

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
		$division_id = input('post.second');
		
		$img_src = input('post.img');
		
		$rule = [
			'name' => 'require|min:2',
			'division_id' => 'require|between:1,100',
			'price' => 'require',
			'stock'  => 'require',
			
		];

		$msg = [
			'name.require' => '商品名称必须填写',
			'name.length'  => '商品名称长度必须大于3位',
			'division_id.require'  => '商品分类必须填写',
			'division_id.between'  => '商品分类必须填写',			
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
			'division_id' => $division_id,
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
				'division_id' => $division_id,
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
	
	public function delSelect()
	{
		 $getid = $_REQUEST['id']; //获取选择的复选框的值
		//$getid = input('post.id');
		 if (!$getid)
        $this->error('未选择记录'); //没选择就提示信息
        $getids = implode(',', $getid); //选择一个以上，就用,把值连接起来(1,2,3)这样
        $id = is_array($getid) ? $getids : $getid; //如果是数组，就把用,连接起来的值覆给$id,否则就覆获取到的没有,号连接起来的值
     	//最后进行数据操作,
        if( $id != ""){
        	$ret = db('goods')->delete($id);
        	if( $ret == false ){
        		$this->error('删除失败');
        	}
        	$this->success('删除成功');
        }
    }
	public function details_del()
	{
		$id = input('get.id');
		if( $id != ""){
			$ret = db('goods_detail')->where("id={$id}")->delete();
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
		var_dump($result);
		$this->assign('result',$result);		

		$field = "division_id,name";
        $category = db('division')->where("level=1")->field($field)->select();
        $this->assign('category', $category);
		return view('edit');
	}
	public function details_edit()
	{
		$id = input('get.id');
		// $result =[];
		$d_list = db('goods_detail')->where("goods_id={$id}")->select();
		// var_dump($d_list);
		
		$this->assign('d_list',$d_list);
		return view('details_edit');
	}

	public function details_add()
	{
		$id = input('get.id');
		$result =[];
		if( $id != ""){
			$result = db('goods')->where("id={$id}")->find();
		}
		$this->assign('result',$result);
		return view('details_add');
	}

	public function details_insert()
	{		

		$goods_id = input('post.goods_id');
		$sort = input('post.sort');
		$img_src= input('post.img_src');
		$rule = [
			'sort' => 'require|between:1,100'			
		];

		$msg = [
			'sort.require' => '图片顺序必须填写',
			'sort.between' => '图片顺序在1-100之间'				
		];

		$data = [
			'goods_id'   => $goods_id,
			'sort'=> $sort,
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
				'goods_id'   => $goods_id,
				'sort'=> $sort,
				'img_src' => $img_src
			];
			$ret = db('goods_detail')->insert($data);
			if( $ret == false ){
				$this->error('添加失败');
			}
			$this->success('添加成功');

	}
	public function details_update()
	{
		$id = input('post.id');
		$goods_id = input('post.goods_id');
		$sort = input('post.sort');
		$img_src= input('post.img_src');
		// $id = $_POST['id'];
		// $goods_id = $_POST['goods_id'];
		// $sort = $_POST['sort'];
		
		// echo "id=",$id;
		// echo "sort=",$sort;
		// echo "img_src=",$img_src;
		$rule = [
			'sort' => 'require|between:1,100'			
		];

		$msg = [
			'sort.require' => '图片顺序必须填写',
			'sort.between' => '图片顺序在1-100之间'	,			
		];

		$data = [
			
			'sort'=> $sort,
			
		];
		$update = [
			'sort' => $sort,
			
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

	    $ret = db('goods_detail')->where("id={$id}")->update($update);

	    if( $ret === false){
	    	$this->error("编辑错误");
	    }
	    if( $ret === 0){
	    	$this->error("没有任何修改");
	    }
	    $this->success("修改成功");
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
		$division_id = input('post.second');
		$img_src = input('post.img_src');
    	
    	$rule = [
		
		  	'name' => 'require|min:2',
			'price' => 'require',
			'stock'  => 'require',
			'division_id'  => 'require|between:1,100',
		 
		];

		$msg = [
		    'name.require' => '商品名称必须填写',
			'name.length'  => '名称长度必须大于3位',
			'division_id.require' => '商品分类必须填写',
			'division_id.between' => '商品分类必须填写',
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
			'division_id' => $division_id,
			'img_src' => $img_src
		];

		$update = [
			'name'   => $name,
			'price'=> $price,
			'stock'    => $stock,
			'division_id' => $division_id,
		];
		var_dump($update);
		
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
