<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 2017-4-12
 * Time: 7:51
 */
namespace app\index\controller;//命名空间
use app\index\model\User;//用户模型

/**
 *用户管理
 */

class UserController extends IndexController //继承IndexController
{
    //显示数据列表页面
    public function index()
    {
        //查询表单传递过来的数据
        $name = input('get.name');
        $pageSize = 20;
        $User = new User;
        $users = $User;
        $users = $User->where('user_name','like','%'.$name.'%')->paginate($pageSize);
        //向V层传数据
        $this->assign('users',$users);
        //取回打包后的数据
        $htmls = $this->fetch();
        //将数据返回给用户
        return $htmls;
    }
    //显示新增数据页面
    public function add(){
        // 实例化
        $User = new User;
        // 设置默认值    
        $User->id = '';
        $User->user_number = '';
        $User->user_name = '';
        $User->user_cellphone = '';
        $User->user_address = '';
        $User->team_id = 0;
        $User->group_id = 0;
        $User->user_remarks = '';
        $User->user_password = '';
        $this->assign('User', $User);
        return $this->fetch('edit');
    }


    //新增数据
    public function insert(){
        $message = '';
        $error = '';
        try{
            $User = new User;
            $User->user_number = input('post.user_number');
            $User->user_name = input('post.user_name');
            $User->user_cellphone = input('post.user_cellphone');
            $User->user_address = input('post.user_address');
            $User->team_id = input('post.team_id');
            $User->group_id = input('post.group_id');
            $User->user_remarks = input('post.user_remarks');
            $User->user_password = input('post.user_password');

            if(false === $User->validate(true)->save()){
                $error = '新增失败'.$User->getError();
            }else{
                $message = $User->user_name .'新增成功';
            }
        }catch(\Exception $e){
            $error = '系统错误:'.$e->getMessage();
        }

        if($error === ''){
            return $this->success($message,url('index'));
        }else{
            return $this->error($error);
        }
    }

    //删除数据库数据
    public function delete(){
        $message = '删除成功';
        $error = '';
        try{
            $user_number = input('get.user_number/d');
            $User = User::get($user_number);
            if(false === $User){
                throw new \Exception('不存在员工号为'.$user_number.'的用户，删除失败!!!');
            }
            if(false === $User->delete()){
                throw new \Exception('删除失败:'.$User->getError());
            }
            return $this->success($message,url('index'));
        }catch(\Exception $e){
            return $this->error('系统错误'.$e->getMessage());
        }

    }

    //显示数据编辑页面
    public function edit(){
        try{
            $user_number = input('get.user_number/d');
            if(false === $User = User::get($user_number)){
                return '系统未找到员工号为'.$user_number.'的记录';
            }
            $this->assign('User',$User);
            $htmls = $this->fetch();
            return $htmls;
        }catch(\Exception $e){
            return '系统错误'.$e->getMessage();
        }
    }

    //更新数据
    public function update(){
        $message = '更新成功';
        $error = '';
        try{
            $user_number = input('get.user_number/d');
            $User = User::get($user_number);
            $User->user_number = input('post.user_number');
            $User->user_name = input('post.user_name');
            $User->user_cellphone = input('post.user_cellphone');
            $User->user_address = input('post.user_address');
            $User->team_id = input('post.team_id');
            $User->group_id = input('post.group_id');
            $User->user_remarks = input('post.user_remarks');
            $User->user_password = input('post.user_password');              

            if(false === $User->validate(true)->save()){
                $error = '更新失败'.$User->getError();
            }

        } catch(\Exception $e){
            $error = '系统错误'.$e->getMessage();
        }

        if($error === ''){
            return $this->success($message,url('index'));
        }else{
            return $this->error($error);
        }
    }

    //新增或修改数据
    public function save(){
        $user_number = input('get.user_number/d');
        if(false === $User = User::get($user_number)){
            $User = new User;
            $User->username = input('post.username');
        }
        $User->user_number = input('post.user_number');
        $User->user_name = input('post.user_name');
        $User->user_cellphone = input('post.user_cellphone');
        $User->user_address = input('post.user_address');
        $User->team_id = input('post.team_id');
        $User->group_id = input('post.group_id');
        $User->user_remarks = input('post.user_remarks');
        $User->user_password = input('post.user_password');    
        if(false === $User->validate()->save()){
            return $this->error('操作失败'.$User->getError());
        }
        return $this->success('操作成功',url('index'));
    }

}
