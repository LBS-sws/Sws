<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderWechatModel extends Model{
    //自動驗證
    protected $_validate = array(
        array('openid','require','页面异常！'),
        array('order_sta_id','require','订单编号必须填写！'),
        array('order_sta_id','checkOrderId','訂單id不存在！',0,'callback'),
    );

    //自動完成
    protected $_auto = array (
        array('lcd','getNowDate',self::MODEL_INSERT,'callback'),
        array('weChat_state','setInsetState',self::MODEL_INSERT,'callback'),
/*        array('service_time','setNull',2,'callback'),
        array('service_time_end','setNull',2,'callback'),*/
    );

    protected $insertFields = 'openid,order_sta_id,latitude,longitude,weChat_state,lcd'; // 新增数据的时候允许写入
    protected $updateFields = 'weChat_state,weChat_remark'; // 编辑数据的时候只允许写入

    public function checkOrderId($order_id){
        $map['id'] = $order_id;
        $rs = D("OrderSta")->where($map)->find();
        if ($rs){
            return true;
        }else{
            return false;
        }
    }

    public function getNowDate($str){
        return date("Y-m-d H:i:s");
    }

    public function setInsetState($str){
        return 0;
    }
}