<?php
/**
 * Created by PhpStorm.
 * Dictionary: icbce
 * Date: 2017-4-10
 * Time: 17:46
 */

namespace app\index\controller;//命名空间
use app\index\model\Dictionary;//用户模型

/**
 *字典管理
 */

class DictionaryController extends IndexController //继承IndexController
{
    //显示数据列表页面
    public function index()
    {
        //查询表单传递过来的数据
        $name = input('get.name');
        $pageSize = 20;
        $Dictionary = new Dictionary;
        $dictionarys = $Dictionary;
        $dictionarys = $Dictionary->where('dictionary_type','like','%'.$name.'%')->paginate($pageSize);
        //向V层传数据
        $this->assign('dictionarys',$dictionarys);
        //取回打包后的数据
        $htmls = $this->fetch();
        //将数据返回给用户
        return $htmls;
    }
    //显示新增数据页面
    public function add(){
        // 实例化
        $Dictionary = new Dictionary;
        // 设置默认值    
        $Dictionary->id = '';
        $Dictionary->dictionary_type = '';
        $Dictionary->dictionary_value = '';
        $Dictionary->dictionary_detail = '';
        $Dictionary->dictionary_sequence = '';
        $Dictionary->dictionary_remarks = '';
        $this->assign('Dictionary', $Dictionary);
        return $this->fetch('edit');
    }


    //新增数据
    public function insert(){
        $message = '';
        $error = '';
        try{
            $Dictionary = new Dictionary;
            $Dictionary->id = input('post.id');
            $Dictionary->dictionary_type = input('post.dictionary_type');
            $Dictionary->dictionary_value = input('post.dictionary_value');
            $Dictionary->dictionary_detail = input('post.dictionary_detail');
            $Dictionary->dictionary_sequence = input('post.dictionary_sequence');
            $Dictionary->dictionary_remarks = input('post.dictionary_remarks');
            if(false === $Dictionary->validate(true)->save()){
                $error = '新增失败'.$Dictionary->getError();
            }else{
                $message = $Dictionary->dictionary_type .'新增成功';
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
            $id = input('get.id/d');
            $Dictionary = Dictionary::get($id);
            if(false === $Dictionary){
                throw new \Exception('不存在序号为'.$id.'的用户，删除失败!!!');
            }
            if(false === $Dictionary->delete()){
                throw new \Exception('删除失败:'.$Dictionary->getError());
            }
            return $this->success($message,url('index'));
        }catch(\Exception $e){
            return $this->error('系统错误'.$e->getMessage());
        }

    }

    //显示数据编辑页面
    public function edit(){
        try{
            $id = input('get.id/d');
            if(false === $Dictionary = Dictionary::get($id)){
                return '系统未找到序号为'.$id.'的记录';
            }
            $this->assign('Dictionary',$Dictionary);
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
            $id = input('get.id/d');
            $Dictionary = Dictionary::get($id);
            $Dictionary->id = input('post.id');
            $Dictionary->dictionary_type = input('post.dictionary_type');
            $Dictionary->dictionary_value = input('post.dictionary_value');
            $Dictionary->dictionary_detail = input('post.dictionary_detail');
            $Dictionary->dictionary_sequence = input('post.dictionary_sequence');
            $Dictionary->dictionary_remarks = input('post.dictionary_remarks');
          

            if(false === $Dictionary->validate(true)->save()){
                $error = '更新失败'.$Dictionary->getError();
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
        $id = input('get.id/d');
        if(false === $Dictionary = Dictionary::get($id)){
            $Dictionary = new Dictionary;
            $Dictionary->username = input('post.username');
        }
        $Dictionary->id = input('post.id');
        $Dictionary->dictionary_type = input('post.dictionary_type');
        $Dictionary->dictionary_value = input('post.dictionary_value');
        $Dictionary->dictionary_detail = input('post.dictionary_detail');
        $Dictionary->dictionary_sequence = input('post.dictionary_sequence');
        $Dictionary->dictionary_remarks = input('post.dictionary_remarks');
  
        if(false === $Dictionary->validate()->save()){
            return $this->error('操作失败'.$Dictionary->getError());
        }
        return $this->success('操作成功',url('index'));
    }

}
