<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;

class AreaController extends BaseController {
    public function index(){
        $areaViewModel = D("AreaView");
        //$map["id"] = array('gt',0);
        $user = session("user");
        $map["city_id"]= array(array('in',$user["city_auth"]),array('gt',0));
        $areaList = $areaViewModel->where($map)->order('id desc')->select();
        $this->assign("areaList",$areaList);
        $this->assign("name","area_name".getNamePrefix());
        $this->assign("cityName","city_name".getNamePrefix());
        $this->display();
	}

    public function add(){
        $user = session("user");
        $cityMap["id"]=array('in',$user["city_auth"]);
        $cityModel = D("City");
        $cityList = $cityModel->where($cityMap)->order('region_id,z_index asc')->getField('id,currency_type,city_name'.getNamePrefix());
        $this->assign("cityList",$cityList);
        $this->assign("cityName","city_name".getNamePrefix());
        $this->display("form");
	}

    public function edit($index){
        $user = session("user");
        $areaModel = D("Area");
        $cityModel = D("City");
        $map["id"] = $index;
        $map["city_id"]= array('in',$user["city_auth"]);
        $areaList = $areaModel->where($map)->find();
        if($areaList){
            $cityMap["id"]=array('in',$user["city_auth"]);
            $cityList = $cityModel->where($cityMap)->order('region_id,z_index asc')->getField('id,currency_type,city_name'.getNamePrefix());
            $this->assign("areaList",$areaList);
            $this->assign("cityList",$cityList);
            $this->assign("cityName","city_name".getNamePrefix());
            $this->display("form");
        }else{
            $this->error(L("illegal_request"),"/sws/area/index",5);
        }
	}

    public function delete(){
        if(IS_AJAX){
            $user = session("user");
            $areaModel = D("Area");
            $orderModel = D("Order");
            $map["id"] = I("id");
            $map["city_id"]= array('in',$user["city_auth"]);
            $rs = $orderModel->where(array("area_id"=>I("id")))->count();
            if($rs > 0){
                $this->ajaxReturn(array("status"=>0));
            }else{
                $result = $areaModel->where($map)->delete();
                if($result){
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/area/index",5);
        }
	}

    public function ajaxCheckAreaName(){
        if(IS_AJAX){
            $name = I("value");
            if(empty($name)){
                $this->ajaxReturn(array("status"=>1));
            }else{
                $areaModel = D("Area");
                $map[$name] = I("name");
                $city_id = I("city_id",0);
                if(!empty($city_id)){
                    $map["city_id"] = $city_id;
                }
                $map["id"] = array('neq',I("id",0));
                $result = $areaModel->where($map)->find();
                if($result){
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/area/index",5);
        }
	}

    public function save(){
        $areaModel = D("Area");
        $data = I("post.");
        if (IS_POST){
            if (!$areaModel->create($data)){
                $this->error($areaModel->getError(),"/sws/area/index",5);
            }else{
                if(empty($data["id"])){
                    $result  = $areaModel->add();
                }else{
                    $areaModel->where(array("id"=>$data["id"]))->save();
                    $result  = $data["id"];
                }
                if($result !== false){
                    session('TOP_TITLE',L('save_ok'));
                    $this->redirect("/sws/area/edit",array("index"=>$result));
                }else{
                    $this->error(L("operation_error"),"/sws/area/index",5);
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/area/index",5);
        }
	}
}