<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
class Account extends Common
{
    public function add()
    {
    	return view('add');
    }
	public function insert()
	{
		
		

	    $name = input('post.name','','strip_tags');
		$account = input('post.account','','htmlspecialchars');
		$pwd = input('post.pwd');
		$repwd = input('post.repwd');
    	$img_src =input('post.img');
    	$rule = [
		    'account' => 'require|min:4|unique:admin,account',
		  	'name' => 'require|min:2',
		  	'pwd'  => 'require|min:6',
		  	'repwd'  => 'require|confirm:pwd'
		];

		$msg = [
		    'account.require' => '账号必须填写',
		    'account.length'  => '账号长度必须大于3位',
		    'account.unique'  => '账号已经被注册',
		    'pwd.min'  => '密码长度必须大于6位',
		    'repwd.confirm'  => '两次密码必须一致'
		];

		$data = [
		    'name'   => $name,
		    'account'=> $account,
		    'pwd'    => $pwd,
			'repwd'  => $repwd
		];
	
		$validate = new Validate($rule,$msg);
		$result   = $validate->check($data);
		if(!$result){
			$this->error($validate->getError());
			die;
		}

    	//插入数据
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
			    'name'   => $name,
			    'account'=> $account,
			    'pwd'    => md5($pwd),
				'head_img'  => $img_src
			];
			$ret = db('admin')->insert($data);
	    	if( $ret == false ){
	    		$this->error('添加失败');
	    	}
	    	$this->success('添加成功');

	}

	public function alist()
	{
		$list = db('admin')->paginate(10);

		$this->assign('result',$list);
		return view('alist');
	}

	public function edit()
	{
		$id = input('get.id');
		$result =[];
		if( $id != ""){
			$result = db('admin')->where("id={$id}")->find();
		}
		$this->assign('result',$result);
		return view('edit');
	}

	public function del()
	{
		$id = input('get.id');
		if( $id != ""){
			$ret = db('admin')->where("id={$id}")->delete();
			if( $ret == false ){
	    		$this->error('删除失败');
	    	}
	    	$this->success('删除成功');
		}

	}
	public function update()
	{
		$name = input('post.name','','strip_tags');
		$id = input('post.id');
		$pwd = input('post.pwd');
		$repwd = input('post.repwd');
    	
    	$rule = [
		
		  	'name' => 'require|min:2',
		 
		];

		$msg = [
		    'name.require' => '名字必须填写',
		    'name.length'  => '名字长度必须大于3位',
		    
		    'pwd.min'  => '密码长度必须大于6位',
		    'repwd.confirm'  => '两次密码必须一致'
		];

		$data = [
		    'name'   => $name,
		   
		    'pwd'    => $pwd,
			'repwd'  => $repwd
		];

		$update = [
			'name'   => $name,
		];
		if( $pwd != "")
		{
			$rule['pwd']   = 'require|min:6';
			$rule['repwd'] = 'require|confirm:pwd';
			$update['pwd'] = md5($pwd);
		}
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
	           $update['head_img'] = $img_src;

	        }else{
	            // 上传失败获取错误信息
	            $this->error($file->getError());
	        }
	    }

	    $ret = db('admin')->where("id={$id}")->update($update);

	    if( $ret === false){
	    	$this->error("编辑错误");
	    }
	    if( $ret === 0){
	    	$this->error("没有任何修改");
	    }
	    $this->success("修改成功");
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
}
		


