<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;

class BusinessController extends BaseController {
    public function index(){
        $user = session("user");
        $map["city_id"]= array('in',$user["city_auth"]);
        $businessViewModel = D("BusinessView");
        $businessList = $businessViewModel->where($map)->order('id desc')->select();
        $this->assign("businessList",$businessList);
        $this->assign("name","name".getNamePrefix());
        $this->assign("cityName","city_name".getNamePrefix());
        $this->display();
	}

    public function add(){
        $user = session("user");
        $cityMap["id"]=array('in',$user["city_auth"]);
        $cityModel = D("City");
        $businessList = array("type"=>1);
        $cityList = $cityModel->where($cityMap)->order('region_id,z_index asc')->getField('id,currency_type,b_unit,city_name'.getNamePrefix());
        $this->assign("cityList",$cityList);
        $this->assign("businessList",$businessList);
        $this->assign("cityName","city_name".getNamePrefix());
        $this->display("form");
	}

    public function edit($index){
        $user = session("user");
        $BusinessModel = D("Business");
        $cityModel = D("City");
        $map["id"] = $index;
        $map["city_id"]= array('in',$user["city_auth"]);
        $businessList = $BusinessModel->where($map)->find();
        if($businessList){
            $cityMap["id"]=array('in',$user["city_auth"]);
            $cityList = $cityModel->where($cityMap)->order('region_id,z_index asc')->getField('id,currency_type,b_unit,city_name'.getNamePrefix());
            $this->assign("cityList",$cityList);
            $this->assign("businessList",$businessList);
            $this->assign("cityName","city_name".getNamePrefix());
            $this->display("form");
        }else{
            $this->error(L("illegal_request"),"/sws/business/index",5);
        }
	}

    public function delete(){
        if(IS_AJAX){
            $user = session("user");
            $BusinessModel = D("Business");
            $orderBusModel = D("OrderBus");
            $map["id"] = I("id");
            $map["city_id"]= array('in',$user["city_auth"]);
            $validate["bus_id"] = I("id");
            $rs = $orderBusModel->where($validate)->count();
            if($rs > 0){
                $this->ajaxReturn(array("status"=>0));
            }else{
                $result = $BusinessModel->where($map)->delete();
                if($result){
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/business/index",5);
        }
	}

    public function ajaxCheckBusinessName(){
        if(IS_AJAX){
            $name = I("value");
            if(empty($name)){
                $this->ajaxReturn(array("status"=>1));
            }else{
                $BusinessModel = D("Business");
                $map[$name] = I("name");
                $city_id = I("city_id",0);
                if(!empty($city_id)){
                    $map["city_id"] = $city_id;
                }
                $map["id"] = array('neq',I("id",0));
                $result = $BusinessModel->where($map)->find();
                if($result){
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/business/index",5);
        }
	}

    public function save(){
        $BusinessModel = D("Business");
        $data = I("post.");
        if (IS_POST){
            if (!$BusinessModel->create($data)){
                $this->error($BusinessModel->getError(),"/sws/business/index",5);
            }else{
                if(empty($data["id"])){
                    $result  = $BusinessModel->add();
                }else{
                    $BusinessModel->where(array("id"=>$data["id"]))->save();
                    $result  = $data["id"];
                }
                if($result !== false){
                    session('TOP_TITLE',L('save_ok'));
                    $this->redirect("/sws/business/edit",array("index"=>$result));
                }else{
                    $this->error(L("operation_error"),"/sws/business/index",5);
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/business/index",5);
        }
	}
}