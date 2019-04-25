<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class OrderStaViewModel extends Model\ViewModel {
    public $viewFields = array(
        'OrderSta'=>array(
            'id','order_id','s_code','s_type','kehu_lang','remark','status','total_price','lcu','luu','lcd','lud','send_email','service_time','service_time_end','kehu_set',
            '_type'=>'LEFT'
        ),
        't_order'=>array(
            'order_type','order_code','order_name','appellation','email','phone',
            'house_type','city_id','area_id','address','door_in','door_out','number',
            'question','token',
            '_table'=>"quote_order",
            '_on'=>'t_order.id=OrderSta.order_id'
        ),
        'City'=>array(
            'region_id'=>'region_id',
            'city_name'=>'city_name',
            'city_name_tw'=>'city_name_tw',
            'city_name_us'=>'city_name_us',
            'currency_type'=>'currency_type',
            'other_price'=>'other_price',
            'other_min'=>'other_min',
            'b_unit'=>'b_unit',
            'company'=>'company',
            'company_us'=>'company_us',
            'company_tw'=>'company_tw',
            'terms'=>'terms',
            'terms_us'=>'terms_us',
            'terms_tw'=>'terms_tw',
            'seal'=>'seal',
            '_on'=>'t_order.city_id=City.id'
        ),
        'Region'=>array(
            'region_name'=>'region_name',
            'region_name_tw'=>'region_name_tw',
            'region_name_us'=>'region_name_us',
            'web_prefix'=>'web_prefix',
            'www_fix'=>'www_fix',
            'calculation'=>'calculation',
            '_on'=>'City.region_id=Region.id'
        ),
       'Area'=>array(
            'area_name'=>'area_name',
            'area_name_tw'=>'area_name_tw',
            'area_name_us'=>'area_name_us',
            'min_price'=>'min_price',
            'area_price'=>'area_price',
            '_on'=>'t_order.area_id=Area.id'
        )
    );
}