<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class RegionModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('region_name','require','地區名字（簡）必须！'),
        array('region_name_tw','require','地區名字（繁）必须！'),
        array('region_name_us','require','地區名字（英）必须！'),
        array('region_name','checkName','地區名字（簡）已经存在！',2,'callback'),
        array('region_name_tw','checkNameTw','地區名字（繁）已经存在！',2,'callback'),
        array('region_name_us','checkNameUs','地區名字（英）已经存在！',2,'callback'),
        array('z_index','/^[0-9]+$/','必須為數字',2),
        array('web_prefix','require','訂單編號前綴必须！'),
        array('www_fix','require','網站前綴必须！'),
        array('calculation','require','害虫合并计算必须！'),
        //array('region_email','checkEmail','郵箱格式不正确',0,'function'),
    );

    //自動完成
    protected $_auto = array (
        array('web_prefix','setWebPrefix',3,'callback'),
        array('www_fix','setWwwfix',3,'callback'),
        array('calculation','setCalculation',3,'callback'),
    );

    protected $insertFields = 'region_name,region_name_tw,region_name_us,z_index,web_prefix,www_fix,calculation'; // 新增数据的时候允许写入
    protected $updateFields = 'region_name,region_name_tw,region_name_us,z_index,web_prefix,www_fix,calculation'; // 编辑数据的时候只允许写入

    public function checkName($name){
        $map['region_name'] = $name;
        $map['id'] = array('neq',I('id',0));
        $rs = D("Region")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameTw($name){
        $map['region_name_tw'] = $name;
        $map['id'] = array('neq',I('id',0));
        $rs = D("Region")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }
    public function checkNameUs($name){
        $map['region_name_us'] = $name;
        $map['id'] = array('neq',I('id',0));
        $rs = D("Region")->where($map)->find();
        if ($rs){
            return false;
        }else{
            return true;
        }
    }

    public function setWebPrefix($web_prefix){
        if(empty($web_prefix)){
            return 'cn';
        }else{
            return $web_prefix;
        }
    }

    public function setWwwfix($www_fix){
        if(stripos($www_fix,"|")===0&&strripos($www_fix,"|")===strlen($www_fix)-1){
            return $www_fix;
        }else{
            return '|'.$www_fix.'|';
        }
    }

    public function setCalculation($calculation){
        $calculation = is_numeric($calculation)?$calculation:0;
        $calculation = intval($calculation);
        if(in_array($calculation,array(0,1))){
            return $calculation;
        }else{
            return 0;
        }
    }
}