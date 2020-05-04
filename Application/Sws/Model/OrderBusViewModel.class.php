<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderBusViewModel extends Model\ViewModel {
    public $viewFields = array(
        'OrderBus'=>array(
            'id','order_id','bus_id','lcu','luu',
            '_type'=>'LEFT'
        ),
        'Business'=>array(
            'name'=>'name',
            'name_us'=>'name_us',
            'name_tw'=>'name_tw',
            'type'=>'type',
            'price'=>'price',
            'city_id'=>'city_id',
            '_on'=>'OrderBus.bus_id=Business.id'
        )
    );
}