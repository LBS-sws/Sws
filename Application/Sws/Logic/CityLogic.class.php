<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Logic;
use Sws\Model\CityModel;

class CityLogic extends CityModel {

    //獲取所有的城市列表
    public function getAllCityList(){
        return $this->select();
    }

    //獲取所有的城市列表
    public function getCityListToId($city_id){
        return $this->where("id=$city_id")->getField();
    }

    public function getModel(){
        return $this;
    }
}