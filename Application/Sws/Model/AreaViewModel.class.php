<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class AreaViewModel extends Model\ViewModel {
    public $viewFields = array(
        'Area'=>array('id','city_id','area_name','area_name_tw','area_name_us','area_price','min_price','z_index','_type'=>'LEFT'),
        'City'=>array('city_name'=>'city_name','city_name_tw'=>'city_name_tw','city_name_us'=>'city_name_us','currency_type'=>'currency_type', '_on'=>'Area.city_id=City.id')
    );
}