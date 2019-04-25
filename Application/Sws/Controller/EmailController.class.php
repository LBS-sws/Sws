<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;

class EmailController extends BaseController {
    public function index(){
        $emailModel = D("Email");
        $emailList = $emailModel->order('id desc')->select();
        $this->assign("emailList",$emailList);
        $this->display();
	}

    public function add(){
        $this->display("form");
	}

    public function edit($index){
        $emailModel = D("Email");
        $map["id"] = $index;
        $emailList = $emailModel->where($map)->find();
        $this->assign("emailList",$emailList);
        $this->display("form");
	}

    public function delete(){
        if(IS_AJAX){
            $emailModel = D("Email");

            $result1 = $emailModel->where(array("id"=>I("id")))->find();
            if(!$result1){
                $this->ajaxReturn(array("status"=>0));
            }else{
                $map["id"] = I("id");
                $emailModel->where($map)->delete();
                $this->ajaxReturn(array("status"=>1));
            }
        }else{
            $this->error(L("illegal_request"),"/sws/email/index",5);
        }
	}

    public function ajaxCheckEmailName(){
        if(IS_AJAX){
            $name = I("value");
            if(empty($name)){
                $this->ajaxReturn(array("status"=>1));
            }else{
                $emailModel = D("Email");
                $map[$name] = I("name");
                $map["id"] = array('neq',I("id",0));
                $result = $emailModel->where($map)->find();
                if($result){
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/email/index",5);
        }
	}

    public function save(){
        $emailModel = D("Email");
        $data = I("post.");
        if (IS_POST){
            if (!$emailModel->create($data)){
                $this->error($emailModel->getError(),"/sws/email/index",5);
            }else{
                if(empty($data["id"])){
                    $result  = $emailModel->add();
                }else{
                    $emailModel->where(array("id"=>$data["id"]))->save();
                    $result = $data["id"];
                }
                if($result !== false){
                    session('TOP_TITLE',L('save_ok'));
                    $this->redirect("/sws/email/edit",array("index"=>$result));
                }else{
                    $this->error(L("operation_error"),"/sws/email/index",5);
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/email/index",5);
        }
	}
}