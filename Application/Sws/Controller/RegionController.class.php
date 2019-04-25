<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;

class RegionController extends BaseController {
    public function index(){
        $regionModel = D("Region");
        $regionList = $regionModel->order('id desc')->select();
        $this->assign("regionList",$regionList);
        $this->assign("name","region_name".getNamePrefix());
        $this->display();
	}

    public function add(){
        $this->display("form");
	}

    public function edit($index){
        $regionModel = D("Region");
        $map["id"] = $index;
        $regionList = $regionModel->where($map)->find();
        $regionList['www_fix'] = removeOneAndEnd($regionList['www_fix']);
        $this->assign("regionList",$regionList);
        $this->display("form");
	}

    public function delete(){
        if(IS_AJAX){
            $cityModel = D("City");
            $regionModel = D("Region");

            $result1 = $cityModel->where(array("region_id"=>I("id")))->find();
            if($result1){
                $this->ajaxReturn(array("status"=>0));
            }else{
                $map["id"] = I("id");
                $regionModel->where($map)->delete();
                $this->ajaxReturn(array("status"=>1));
            }
        }else{
            $this->error(L("illegal_request"),"/sws/region/index",5);
        }
	}

    public function ajaxCheckRegionName(){
        if(IS_AJAX){
            $name = I("value");
            if(empty($name)){
                $this->ajaxReturn(array("status"=>1));
            }else{
                $regionModel = D("Region");
                $map[$name] = I("name");
                $map["id"] = array('neq',I("id",0));
                $result = $regionModel->where($map)->find();
                if($result){
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/region/index",5);
        }
	}

    public function save(){
        $regionModel = D("Region");
        $data = I("post.");
        if (IS_POST){
            if (!$regionModel->create($data)){
                $this->error($regionModel->getError(),"/sws/region/index",5);
            }else{
                if(empty($data["id"])){
                    $result  = $regionModel->add();
                }else{
                    $regionModel->where(array("id"=>$data["id"]))->save();
                    $result  = $data["id"];
                }
                if($result !== false){
                    session('TOP_TITLE',L('save_ok'));
                    $this->redirect("/sws/region/edit",array("index"=>$result));
                }else{
                    $this->error(L("operation_error"),"/sws/region/index",5);
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/region/index",5);
        }
	}
}