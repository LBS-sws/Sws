<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class CityViewModel extends Model\ViewModel {
    public $viewFields = array(
        //'region_name,region_name_tw,region_name_us,z_index,web_prefix'
        'City'=>array(
            'id','city_name','city_name_tw','city_name_us','other_open','other_price','other_min','currency_type','region_id','b_unit','company','company_us','company_tw','terms','terms_us','terms_tw','seal','z_index'
        ,'_type'=>'LEFT'
        ),
        'Region'=>array('region_name'=>'region_name','region_name_tw'=>'region_name_tw','region_name_us'=>'region_name_us','web_prefix'=>'web_prefix','www_fix'=>'www_fix', '_on'=>'City.region_id=Region.id')
    );
}