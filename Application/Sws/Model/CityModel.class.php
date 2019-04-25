<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class CityModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('city_name','require','城市名字（簡）必须！'),
        array('city_name_tw','require','城市名字（繁）必须！'),
        array('city_name_us','require','城市名字（英）必须！'),
        array('b_unit','require','面積單位必须！'),
        array('region_id','require','地區必须！'),
        array('city_name','checkName','城市名字（簡）已经存在！',2,'callback'),
        array('city_name_tw','checkNameTw','城市名字（繁）已经存在！',2,'callback'),
        array('city_name_us','checkNameUs','城市名字（英）已经存在！',2,'callback'),
        array('z_index','/^[0-9]+$/','必須為數字',2),
        array('region_id','/^[0-9]+$/','地區不存在',2),
        array('b_unit','checkDoorUnit','面積單位輸入有誤',2,'function'),
        //array('other_open','/^[0-9]+$/','必須為數字',2)
    );

    //自動完成
    protected $_auto = array (
        array('other_price','setPrice',3,'callback'),
        array('other_min','setPrice',3,'callback'),
        array('currency_type','setCurrency',3,'callback'),
    );

    protected $insertFields = 'city_name,city_name_tw,city_name_us,z_index,other_open,other_price,other_min,currency_type,region_id,b_unit,company,company_us,company_tw,seal,terms,terms_tw,terms_us'; // 新增数据的时候允许写入
    protected $updateFields = 'city_name,city_name_tw,city_name_us,z_index,other_open,other_price,other_min,currency_type,region_id,b_unit,company,company_us,company_tw,seal,terms,terms_tw,terms_us'; // 编辑数据的时候只允许写入

    public function checkName($name){
        $map['city_name'] = $name;
        $map['id'] = array('neq',I('id',0));
        $rs = D("City")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameTw($name){
        $map['city_name_tw'] = $name;
        $map['id'] = array('neq',I('id',0));
        $rs = D("City")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameUs($name){
        $map['city_name_us'] = $name;
        $map['id'] = array('neq',I('id',0));
        $rs = D("City")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function setPrice($other_price){
        $otherOpen = I("other_open");
        if(empty($otherOpen)){
            return null;
        }else{
            $other_price = floatval($other_price);
        }
        return $other_price;
    }
    //自動完成貨幣類型
    public function setCurrency($currency_type){
        $currList = array('HK$','NT$','MOP$','RMB');
        if(in_array($currency_type,$currList)){
            return $currency_type;
        }
        return 'RMB';
    }
}