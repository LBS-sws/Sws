<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class AreaModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('city_id','require','城市必须！'),
        array('area_name','require','區域名字（簡）必须！'),
        array('area_name_tw','require','區域名字（繁）必须！'),
        array('area_name_us','require','區域名字（英）必须！'),
        array('area_name','checkName','區域名字（簡）已经存在！',2,'callback'),
        array('area_name_tw','checkNameTw','區域名字（繁）已经存在！',2,'callback'),
        array('area_name_us','checkNameUs','區域名字（英）已经存在！',2,'callback'),
        array('z_index','/^[0-9]+$/','必須為數字',2),
        array('min_price','checkNumber','最小價格必須為數字',2,'function'),
        array('area_price','checkNumber','交通費用必須為數字',2,'function'),
        array('city_id','checkCity','城市不存在！',2,'function'),
        //array('other_open','/^[0-9]+$/','必須為數字',2)
    );

    //自動完成
    protected $_auto = array (
    );

    protected $insertFields = 'city_id,area_name,area_name_tw,area_name_us,area_price,min_price,z_index'; // 新增数据的时候允许写入
    protected $updateFields = 'city_id,area_name,area_name_tw,area_name_us,area_price,min_price,z_index'; // 编辑数据的时候只允许写入

    public function checkName($name){
        $map['area_name'] = $name;
        $map['id'] = array('neq',I('id',0));
        $map['city_id'] = I('city_id',0);
        $rs = D("Area")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameTw($name){
        $map['area_name_tw'] = $name;
        $map['id'] = array('neq',I('id',0));
        $map['city_id'] = I('city_id',0);
        $rs = D("Area")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameUs($name){
        $map['area_name_us'] = $name;
        $map['id'] = array('neq',I('id',0));
        $map['city_id'] = I('city_id',0);
        $rs = D("Area")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
}