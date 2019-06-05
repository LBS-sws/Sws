<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('order_name','require','姓名必须！',1),
        array('order_name','checkName','姓名格式不正确',1,'function'),
        array('appellation','require','稱謂必须！',1),
        array('email','require','郵箱必须！',1),
        array('phone','require','電話必须！',1),
        array('token','require','驗證必须！',0),
        //array('house_type','require','客戶類別必须！',1),
        array('city_id','require','城市必须！',1),
        array('area_id','require','區域必须！',1),
        //array('number','require','治理次數！',1),
        array('area_id','/^[0-9]+$/','地區不合法!',1),
        array('city_id','/^[0-9]+$/','城市不合法!',1),
        array('area_id','checkArea','地區數據異常',1,'callback'),
        array('door_in','checkDoor','治理範圍必須為數字',1,'callback'),
        array('token','checkToken','系統異常，請刷新頁面后重新下单',0,'callback',1),
    );

    //自動完成
    protected $_auto = array (
        array('lcu_ip','getLcuIp',self::MODEL_INSERT,'callback'),
        array('from_order','getFromOrder',self::MODEL_INSERT,'callback'),
        array('lcd','getNowDate',self::MODEL_INSERT,'callback'),
        //array('question','strToHtmlspecialchars',self::MODEL_BOTH,'function'),
        array('luu_id','getAdminId',self::MODEL_UPDATE,'callback'),
        array('token','setToken',self::MODEL_INSERT,'callback'),
        array('order_type','setOrderType',self::MODEL_INSERT,'callback'),
    );

    protected $insertFields = 'order_name,order_type,appellation,email,phone,city_id,area_id,door_in,from_order,lcu_ip,lcd,token'; // 新增数据的时候允许写入
    protected $updateFields = 'id,order_name,appellation,email,phone,city_id,area_id,address,door_in,luu_id'; // 编辑数据的时候只允许写入


    public function checkToken($token){
        if (empty($token)){
            return false;
        }
        $orderModel = D("Order");
        $map["token"]=$token;
        $rs = $orderModel->where($map)->find();
        if($rs){
            return false;
        }
        return true;
    }
    public function setToken($token){
        if (empty($token)){
            return date("YmdHsi");
        }
        return $token;
    }
    public function getAdminId($str){
        return session("user")['id'];
    }

    public function checkArea($str){
        $city_id = I("city_id");
        $areaModel = D("Area");
        $cityModel = D("City");
        if(empty($city_id)){
            return false;
        }else{
            //當地區為其它時，城市是否允許
            if(empty($str)){
                $rs = $cityModel->where(array("id"=>$city_id,"other_open"=>1))->find();
                if(!$rs){
                    return false;
                }
            }else{
                $map["city_id"] = $city_id;
                $map["id"] = $str;
                $rs = $areaModel->where($map)->find();
                if(!$rs){
                    return false;
                }
            }
        }
        return true;
    }
    public function checkDoor($str){
        $door_in = I("door_in");
        $door_out = I("door_out");
        if(empty($door_in)&&empty($door_out)){
            return false;
        }else{
            if(!is_numeric($door_in)&&!empty($door_in)){
                return false;
            }
            if(!is_numeric($door_out)&&!empty($door_out)){
                return false;
            }
            return true;
        }
    }

    //自動完成訂單的類型。0：特殊業務  1：普通業務 2：混合業務
    public function setOrderType($str){
        $arr = I("business_id",array());
        $business = D("Business");
        $type = "";
        foreach ($arr as $key=>$val){
            $rs = $business->where(array("id"=>$val))->find();
            if ($type === ""){
                $type = $rs["type"];
            }else{
                if($type != $rs["type"]){
                    $type = 2;
                    break;
                }
            }
        }
        if ($type === ""){
            $type = 1;
        }
        return $type;
    }

    public function getLcuIp($str){
        $ip = get_client_ip();
        return $ip;
    }

    public function getFromOrder($str){
        $openid = session("access_token");//["openid"]
        if(!empty($openid)&&in_array("openid",$openid)){
            return 1;//微信下單
        }else{
            return 0;//PC下單
        }
    }

    public function getNowDate($str){
        return date("Y-m-d H:i:s");
    }

}