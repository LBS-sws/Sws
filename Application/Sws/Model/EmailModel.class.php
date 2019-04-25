<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class EmailModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('email_prefix','require','郵箱前綴必须！'),
        array('email','require','郵箱官網必须！'),
        array('email_prefix','checkName','郵箱前綴已经存在！',2,'callback'),
        //array('email','checkEmail','郵箱格式不正确',0,'function'),
    );

    //自動完成
    protected $_auto = array (
        //array('web_prefix','setWebPrefix',3,'callback'),
    );

    protected $insertFields = 'email_prefix,email'; // 新增数据的时候允许写入
    protected $updateFields = 'email_prefix,email'; // 编辑数据的时候只允许写入

    public function checkName($name){
        $map['email_prefix'] = $name;
        $map['id'] = array('neq',I('id',0));
        $rs = D("Email")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
}