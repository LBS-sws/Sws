<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderHisViewModel extends Model\ViewModel {
    public $viewFields = array(
        'OrderHis'=>array(
            'id','sta_id','status','lcu','lcd',
            '_type'=>'LEFT'
        ),
        'OrderSta'=>array(
            's_code'=>'s_code',
            's_type'=>'s_type',
            '_on'=>'OrderSta.id=OrderHis.sta_id'
        )
    );
}