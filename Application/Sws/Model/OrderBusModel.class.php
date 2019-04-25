<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderBusModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('order_id','require','訂單id必须！'),
        array('bus_id','require','害蟲id必须！'),
        array('bus_type','require','害蟲類型必须！'),
        array('order_id','checkOrderId','訂單id不存在！',0,'callback'),
        array('bus_id','checkBusinessId','害蟲id不存在！',0,'callback'),
        //array('region_email','checkEmail','郵箱格式不正确',0,'function'),
    );

    //自動完成
    protected $_auto = array (
        array('lcu','setLcu',1,'callback'),
        array('lcd','getNowDate',self::MODEL_INSERT,'callback'),
        array('luu','setLcu',2,'callback'),
    );

    protected $insertFields = 'order_id,bus_id,bus_type,lcu,lcd'; // 新增数据的时候允许写入
    protected $updateFields = 'luu'; // 编辑数据的时候只允许写入

    public function checkOrderId($order_id){
        $map['id'] = $order_id;
        $rs = D("Order")->where($map)->find();
        if ($rs){
            return true;
        }else{
            return false;
        }
    }
    public function checkBusinessId($businessId){
        $map['id'] = $businessId;
        $rs = D("Business")->where($map)->find();
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