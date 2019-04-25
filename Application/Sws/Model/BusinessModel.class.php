<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class BusinessModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('city_id','require','名稱必须！',1),
        array('name','require','名稱（簡）必须！',1),
        array('name_tw','require','名稱（繁）必须！',1),
        array('name_us','require','名稱（英）必须！',1),
        array('type','require','類型必须！',1),
        array('price','checkPrice','價格必须！',1,'callback'),
        array('price','checkNumber','價格必須為數字',2,'function'),
        //array('min_price','require','最低價格必须！'),
        array('name','checkName','業務名字（簡）已经存在！',2,'callback'),
        array('name_tw','checkName','業務名字（繁）已经存在！',2,'callback'),
        array('name_us','checkName','業務名字（英）已经存在！',2,'callback'),
        array('city_id','checkCity','城市不存在！',2,'function'),
    );

    //自動完成
    protected $_auto = array (
        array('price','setPrice',self::MODEL_BOTH,'callback'),
    );

    protected $insertFields = 'name,name_tw,name_us,type,price,city_id'; // 新增数据的时候允许写入
    protected $updateFields = 'name,name_tw,name_us,type,price,city_id'; // 编辑数据的时候只允许写入

    public function checkName($name){
        $map['name'] = $name;
        $map['id'] = array('neq',I('id',0));
        $map['city_id'] = I('city_id',0);
        $rs = D("Business")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameTw($name){
        $map['name_tw'] = $name;
        $map['id'] = array('neq',I('id',0));
        $map['city_id'] = I('city_id',0);
        $rs = D("Business")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameUs($name){
        $map['name_us'] = $name;
        $map['id'] = array('neq',I('id',0));
        $map['city_id'] = I('city_id',0);
        $rs = D("Business")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }

    public function checkPrice($price){
        $type = I("type",1);
        if(empty($type)){
            return true;
        }else{
            if(empty($price)){
                return false;
            }else{
                return true;
            }
        }
    }

    public function setPrice($price){
        $type = I("type",1);
        if(empty($type)){
            return "";
        }else{
            return $price;
        }
    }
}