<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderHisModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('sta_id','require','訂單狀態表的id必须！'),
        array('status','require','歷史狀態必须！'),
        array('sta_id','checkStaId','訂單狀態表的id不存在！',0,'callback'),
        //array('region_email','checkEmail','郵箱格式不正确',0,'function'),
    );

    //自動完成
    protected $_auto = array (
        array('lcu','setLcu',3,'callback'),
        array('lcd','getNowDate',self::MODEL_INSERT,'callback'),
    );

    protected $insertFields = 'sta_id,status,lcu,lcd'; // 新增数据的时候允许写入
    protected $updateFields = 'status'; // 编辑数据的时候只允许写入

    public function checkStaId($sta_id){
        $map['id'] = $sta_id;
        $rs = D("OrderSta")->where($map)->find();
        if ($rs){
            return true;
        }else{
            return false;
        }
    }

    public function setLcu($arr){
        if(session('?user')){
            $user = session("user");
            return $user["user_name"];
        }else{
            return "客戶";
        }
    }
    public function getNowDate($str){
        return date("Y-m-d H:i:s");
    }
}