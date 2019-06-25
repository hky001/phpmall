<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
class Stock extends Common
{
	
	public function add()
	{
		return view('add');		
	}
	public function ruku()
	{
		$stock_id = input('get.stock_id');
		$stock_w = input('get.stock_w');
		$stock = input('get.stock');
		$goods_id = input('get.goods_id');
		$stock_status = input('get.stock_status');

		if( $stock_id != ""){
			if($stock_status == 0){
				$stock = $stock + $stock_w;
				$update_goods = [
					'stock' => $stock,
				];			
				$ret = db('goods')->where("id={$goods_id}")->update($update_goods);
				if($ret == false){
					$this->error('入库失败');
				}
					$stock_status = 1;
					$update = [
						'stock_status' => $stock_status,
					];
					$ret2 = db('stock')->where("stock_id={$stock_id}")->update($update);
					if( $ret2 == false){
						$this->error('入库失败');
					}
					$this->success('入库成功');
			}else{
				$this->error('该采购已入库');
			}
		}	
	}

	public function alist()
	{
		$list = db('stock')->alias('a')
		->join('goods b','a.goods_id=b.id')->order('stock_id desc')->paginate(10);

		$this->assign('result',$list);
		return view('alist');
	}
	public function insert()
	{		

		$goods_id = input('post.goods_id');
		$stock_w = input('post.stock_w');
		$date = input('post.date');		

		$rule = [
		'goods_id' => 'require',
		'stock_w' => 'require|between:1,10000',
		'date' => 'require',			
		];

		$msg = [
		'goods_id.require' => '商品名称必须填写',
		'stock_w.require'  => '采购量必须填写',
		'stock_w.between'  => '采购量必须大与于1',		
		'date.require'=>'入库日期必须填写',			
		];

		$data = [
		'goods_id'   => $goods_id,
		'stock_w'=> $stock_w,
		'date'    => $date,
		'stock_status' => 0,
		];
		$check=[];
		$check = db('goods')->where("id={$goods_id}")->find();

		if($check != NULL){
			$ret = db('stock')->insert($data);
			if( $ret == false ){
				$this->error('添加失败');
			}
			$this->success('添加成功');
		}else{
			$this->error('不存在该商品');
		}
	}
	
	public function del()
	{
		$stock_id = input('get.stock_id');
		if( $stock_id != ""){
			$ret = db('stock')->where("stock_id={$stock_id}")->delete();
			if( $ret == false ){
				$this->error('删除失败');
			}
			$this->success('删除成功');
		}
	}
	
	
	public function edit()
	{
		$stock_id = input('get.stock_id');
		$stock_status = input('stock_status');
		if($stock_status==0){
			$result =[];
			if( $stock_id != ""){
				$result = db('stock')->alias('a')
				->join('goods b','a.goods_id=b.id')->where("a.stock_id={$stock_id}")->find();
			}

			$this->assign('result',$result);		

			return view('edit');
		}else
			$this->error('该采购已入库，无法修改');
		
	}
	
	public function update()
	{
		$stock_id = input('post.stock_id');
		$stock_w = input('post.stock_w');
		$date = input('post.date');
		
		$rule = [
		
		'stock_w' => 'require|between:1,1000000',
		'date' => 'require',					 
		];

		$msg = [
		'stock_w.require' => '采购量必须填写',
		'stock_w.between'  => '采购量必须填写',
		'date.require' => '入库日期必须填写',

		];

		$update = [
		'stock_id' => $stock_id,
		'stock_w'   => $stock_w,
		'date'=> $date,
		];
		var_dump($update);
		$ret = db('stock')->where("stock_id={$stock_id}")->update($update);

		if( $ret === false){	
			$this->error("编辑错误");
		}
		if( $ret === 0){
			$this->error("没有任何修改");
		}
		$this->success("修改成功");
	}

	
}
