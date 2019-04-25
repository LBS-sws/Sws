<?php
/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/2 0002
 * Time: 上午 10:26
 */

namespace Sws\Model;


use Think\Model;

class UserViewModel extends Model\ViewModel {
    public $viewFields = array(
        'User'=>array('id','user_name','nickname','auth','password','email','city_auth','email_hint','old_email','more_hint','lang','_type'=>'LEFT'),
        //'City'=>array('city_name'=>'city_name', '_on'=>'User.user_city=City.id')
    );
}