<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderStaModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('order_id','require','訂單id必须！'),
        array('order_id','checkOrderId','訂單id不存在！',0,'callback'),
    );

    //自動完成
    protected $_auto = array (
        array('kehu_lang','setLang',1,'callback'),
        array('lcu','setLcu',1,'callback'),
        array('lcd','getNowDate',self::MODEL_INSERT,'callback'),
        array('luu','setLcu',2,'callback'),
/*        array('service_time','setNull',2,'callback'),
        array('service_time_end','setNull',2,'callback'),*/
    );

    protected $insertFields = 'order_id,s_type,s_code,lcu,lcd,kehu_lang'; // 新增数据的时候允许写入
    protected $updateFields = 'id,total_price,remark,status,service_time,kehu_set,service_time_end,luu'; // 编辑数据的时候只允许写入

    public function checkOrderId($order_id){
        $map['id'] = $order_id;
        $rs = D("Order")->where($map)->find();
        if ($rs){
            return true;
        }else{
            return false;
        }
    }
    public function setNull($time){
        if (empty($time)){
            return null;
        }else{
            return $time;
        }
    }

    public function setLang($arr){
        return LANG_SET;
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