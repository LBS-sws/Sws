<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class BusinessViewModel extends Model\ViewModel {
    public $viewFields = array(
        'Business'=>array('id','name','name_tw','name_us','type','price','city_id','_type'=>'LEFT'),
        'City'=>array('city_name'=>'city_name','city_name_tw'=>'city_name_tw','city_name_us'=>'city_name_us','currency_type'=>'currency_type','b_unit'=>'b_unit', '_on'=>'Business.city_id=City.id')
    );
}