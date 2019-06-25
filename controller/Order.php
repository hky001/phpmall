<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
class Order extends Common
{
	public function alist()
	{
		$list = db('order')->alias('a')->join('user b','a.user_id=b.id')->join('goods c','a.goods_id=c.id')->join('order_status d','a.pay_id=d.value')->paginate(10);
		
		foreach ($list as $key => $value) {
			# code...
			// dump($value['address_id']);
			$address = db('address')->where("id={$value['address_id']}")->find();
			// dump($address);
			$arr = [
				'address'=>$address['city']
			];
			
			// dump($arr);
			$value = array_merge($value, $arr);
			// array_walk($value, function (&$value, $key, $arr) {
                            
   // //                      }, $arr);	
			// dump($value);
			// $this->assign('result',$value);
			// return view('alist');
		}
		// dump($list);
		

		$this->assign('result',$list);
		
		return view('alist');
	}
	public function edit()
	{
		$order_id = input('get.order_id');
		$result =[];
		if( $order_id != ""){
			$result = db('order')->alias('a')
			->join('user b','a.user_id=b.id')
			->join('goods c','a.goods_id=c.id')
			->join('order_status d','a.pay_id=d.value')
			->where("order_id={$order_id}")
			->find();
		}

		$this->assign('result',$result);
		return view('edit');
	}
	public function update()
	{
		$order_id = intval(input('post.order_id'));
		$value = intval(input('post.value'));
		var_dump($order_id);
		var_dump($value);
		
    	$rule = [
		  	'value' => 'require',
		];

		$msg = [
		    'value.require' => '订单状态必须选择',
		];

		$update = [
			'pay_id' => $value
		];
	    $ret = db('order')->where("order_id={$order_id}")->update($update);

	    if($value==3){
	    	$dingdan = db('order')->where("order_id={$order_id}")->find();	    	
	    	$goods = db('goods')->where("id={$dingdan['goods_id']}")->find();
	    	$goods['sales']+=1;
	    	$goods = db('goods')->where("id={$dingdan['goods_id']}")->update($goods);
	    }
	    if( $ret === false){
	    	$this->error("编辑错误");
	    }
	    if( $ret === 0){
	    	$this->error("没有任何修改");
	    }
	    $this->success("修改成功");
	}
	public function del()
	{
		$order_id = input('get.order_id');
		if( $order_id != ""){
			$ret = db('order')->where("order_id={$order_id}")->delete();
			if( $ret == false ){
	    		$this->error('删除失败');
	    	}
	    	$this->success('删除成功');
		}
	}
}