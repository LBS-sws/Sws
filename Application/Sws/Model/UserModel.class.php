<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class UserModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('user_name','require','用戶名必须！'),
        array('user_name', '3,15', '不得小于 3 位，不得大于 15 位', 0, 'length'),
        array('email','require','郵箱必须！'),
        //array('user_city','require','城市必须！'),
        array('password','require','密碼必须！',0,'require',1),
        array('user_name','checkName','用戶名已经存在！',2,'callback'),
        //array('user_city','checkCity','城市不存在！',2,'function'),
        array('user_name','checkName','用戶名格式不正确',0,'function'),
        array('password','checkPwd','密码格式不正确',0,'function'),
        array('email','checkEmail','郵箱格式不正确',0,'function'),
        array('lang',array('en-us','zh-cn','zh-tw'),'語言不存在！',0,'in'),
    );

    //自動完成
    protected $_auto = array (
        array('city_auth','getCityAuth',3,'callback'),
        array('auth','getAuth',3,'callback'),
        array('password','getPassword',3,'callback'),
    );

    protected $insertFields = 'password,user_name,email,email_hint,nickname,city_auth,auth,old_email,more_hint,lang'; // 新增数据的时候允许写入
    protected $updateFields = 'password,user_name,email,email_hint,nickname,city_auth,auth,old_email,more_hint,lang'; // 编辑数据的时候只允许写入

    public function checkName($name){
        $map['user_name'] = $name;
        $map['id'] = array('neq',I('id',0));
        //$map['user_city'] = I('user_city',0);
        $rs = D("User")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function getAuth($auth){
        if(!empty($auth)){
            if(is_array($auth)){
                $auth = implode(',',$auth);
            }else{
                $auth = "";
            }
        }else{
            $auth = "";
        }
        return $auth;
    }
    public function getCityAuth($cityAuth){
        if(!empty($cityAuth)){
            if(is_array($cityAuth)){
                $cityAuth = implode(',',$cityAuth);
                $cityAuth=",".$cityAuth.",";
            }else{
                $cityAuth = "";
            }
        }else{
            $cityAuth = "";
        }
        return $cityAuth;
    }

    public function getPassword($password){
        if(!empty($password)){
            return setMD5Pwd($password);
        }else{
            $userList = D("User")->where(array("id"=>I("id")))->find();
            return $userList["password"];
        }
    }
}