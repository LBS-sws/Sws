<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;

class UserController extends BaseController {
    public function index(){
        $userModel = D("User");
        $userList = $userModel->order('id desc')->select();
        $this->assign("userList",$userList);
        $this->assign("hintList",geiEmailHintList());
        $this->display();
	}

    public function add(){
        $prefix = getNamePrefix();
        $cityModel = D("City");
        $regionModel = D("Region");
        $cityList = $cityModel->order('region_id,z_index asc')->getField('id,currency_type,region_id,city_name'.$prefix);
        $regionList = $regionModel->order('id desc')->getField('id,region_name'.$prefix);
        $userList["city_auth"] = array();
        $this->assign("langList",array("","zh-cn"=>"中文简体","zh-tw"=>"中文繁體","en-us"=>"English"));
        $this->assign("prefix",$prefix);
        $this->assign("cityList",$cityList);
        $this->assign("regionList",$regionList);
        $this->assign("userList",$userList);
        $this->assign("emailList",geiEmailHintList());
        $this->display("form");
	}

    public function edit($index){
        $prefix = getNamePrefix();
        $userModel = D("User");
        $cityModel = D("City");
        $regionModel = D("Region");
        $map["id"] = $index;
        $userList = $userModel->where($map)->find();//ASC
        $cityList = $cityModel->order('region_id,z_index asc')->getField('id,currency_type,region_id,city_name'.$prefix);
        $regionList = $regionModel->order('id desc')->getField('id,region_name'.$prefix);

        $userList["city_auth"] = explode(",",$userList["city_auth"]);
        $this->assign("langList",array("","zh-cn"=>"中文简体","zh-tw"=>"中文繁體","en-us"=>"English"));
        $this->assign("prefix",$prefix);
        $this->assign("userList",$userList);
        $this->assign("regionList",$regionList);
        $this->assign("emailList",geiEmailHintList());
        $this->assign("cityList",$cityList);
        $this->display("form");
	}

    public function delete(){
        if(IS_AJAX){
            $userModel = D("User");
            $map["id"] = I("id");
            $result = $userModel->where($map)->delete();
            if($result){
                $this->ajaxReturn(array("status"=>1));
            }else{
                $this->ajaxReturn(array("status"=>0));
            }
        }else{
            $this->error(L("illegal_request"),"/sws/user/index",5);
        }
	}

    public function ajaxCheckUserName(){
        if(IS_AJAX){
            $userModel = D("User");
            $map["user_name"] = I("name");
            $map["id"] = array('neq',I("id",0));
            $result = $userModel->where($map)->find();
            if($result){
                $this->ajaxReturn(array("status"=>1));
            }else{
                $this->ajaxReturn(array("status"=>0));
            }
        }else{
            $this->error(L("illegal_request"),"/sws/user/index",5);
        }
	}

    public function save(){
        $userModel = D("User");
        if (IS_POST){
            $data = I("post.");
            if (!$userModel->create($data)){
                $this->error($userModel->getError(),"/sws/user/index",5);
            }else{
                if(empty($data["id"])){
                    $result  = $userModel->add();
                }else{
                    $userModel->where(array("id"=>$data["id"]))->save();
                    $result  = $data["id"];
                    $this->resetSession();
                }
                if($result !== false){
                    session('TOP_TITLE',L('save_ok'));
                    $this->redirect("/sws/user/edit",array("index"=>$result));
                }else{
                    $this->error(L("operation_error"),"/sws/user/index",5);
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/user/index",5);
        }
	}

}