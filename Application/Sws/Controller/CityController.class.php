<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;
use Sws\Service\PdfService;

class CityController extends BaseController {
    public function index(){
        $user = session("user");
        $map["id"]= array('in',$user["city_auth"]);
        $cityViewModel = D("CityView");
        $cityList = $cityViewModel->where($map)->order('id desc')->select();
        $this->assign("cityList",$cityList);
        //$this->assign("name","city_name".getNamePrefix());
        $this->assign("prefix",getNamePrefix());
        $this->display();
	}

    public function add(){
        $currList = array('','HK$','NT$','MOP$','RMB');
        $regionModel = D("Region");
        $regionList = $regionModel->order('z_index asc')->getField('id,region_name'.getNamePrefix());
        $cityList = array("other_open"=>1);
        $this->assign("cityList",$cityList);
        $this->assign("regionList",$regionList);
        $this->assign("unitList",getDoorUnit());
        $this->assign("currList",$currList);
        $this->display("form");
	}

    public function edit($index){
        $user = session("user");
        $cityAuth = explode(",",$user["city_auth"]);
        if(!in_array($index,$cityAuth)){
            $this->redirect("/sws/city/index");
        }else{
            $prefix = getNamePrefix();
            $currList = array('','HK$','NT$','MOP$','RMB');
            $cityModel = D("City");
            $regionModel = D("Region");
            $map["id"] = $index;
            $cityList = $cityModel->where($map)->find();
            $regionList = $regionModel->order('z_index asc')->getField('id,region_name'.$prefix);
            $this->assign("cityList",$cityList);
            $this->assign("regionList",$regionList);
            $this->assign("unitList",getDoorUnit());
            $this->assign("currList",$currList);
            $this->display("form");
        }
	}

    public function delete(){
        if(IS_AJAX){
            $user = session("user");
            $cityAuth = explode(",",$user["city_auth"]);
            if(!in_array(I("id"),$cityAuth)){
                return false;
            }else{
                $cityModel = D("City");
                $areaModel = D("Area");
                $businessModel = D("Business");
                $userModel = D("User");

                $result1 = $areaModel->where(array("city_id"=>I("id")))->find();
                $result2 = $businessModel->where(array("city_id"=>I("id")))->find();
                //$result3 = $userModel->where(array("user_city"=>I("id")))->find();
                if($result1 || $result2){
                    $this->ajaxReturn(array("status"=>0));
                }else{
                    $map["id"] = I("id");
                    $cityModel->where($map)->delete();
                    $this->ajaxReturn(array("status"=>1));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/city/index",5);
        }
	}

    public function ajaxCheckCityName(){
        if(IS_AJAX){
            $name = I("value");
            if(empty($name)){
                $this->ajaxReturn(array("status"=>1));
            }else{
                $cityModel = D("City");
                $map[$name] = I("name");
                $map["id"] = array('neq',I("id",0));
                $result = $cityModel->where($map)->find();
                if($result){
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0));
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/city/index",5);
        }
	}

    public function save(){
        $cityModel = D("City");
        $data = I("post.");
        if (IS_POST){
            if($_FILES['seal']["error"] === 0){
                $config = array(
                    "maxSize"=>3145728,// 设置附件上传大小
                    "exts"=>array('jpg', 'gif', 'png', 'jpeg'),// 设置附件上传类型
                    "rootPath"=>"./Public/Uploads/",
                    "saveName"=>"time",
                );
                $upload = new \Think\Upload($config);// 实例化上传类
                // 上传单个文件
                $info   =   $upload->uploadOne($_FILES['seal']);
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError(),"/sws/city/index",5);
                    return false;
                }else{// 上传成功 获取上传文件信息
                    $service_url =  $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].__ROOT__."/";
                    $data['seal'] = $service_url."Public/Uploads/".$info['savepath'].$info['savename'];
                }
            }
            if (!$cityModel->create($data)){
                $this->error($cityModel->getError(),"/sws/city/index",5);
            }else{
                if(empty($data["id"])){
                    $result  = $cityModel->add();
                }else{
                    $cityModel->where(array("id"=>$data["id"]))->save();
                    $result = $data["id"];
                    $this->resetSession();
                }
                if($result !== false){
                    session('TOP_TITLE',L('save_ok'));
                    $this->redirect("/sws/city/edit",array("index"=>$result));
                }else{
                    $this->error(L("operation_error"),"/sws/city/index",5);
                }
            }
        }else{
            $this->error(L("illegal_request"),"/sws/city/index",5);
        }
	}

    function ck_upload(){
        $config = array(
            "maxSize"=>3145728,// 设置附件上传大小
            "exts"=>array('jpg', 'gif', 'png', 'jpeg'),// 设置附件上传类型
            "rootPath"=>"./Public/Uploads/",
            "saveName"=>"time",
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['upload']);
        if (!$info) {// 上传错误提示错误信息
            echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction('".$_GET['CKEditorFuncNum']."', '/', '上传失败," . $upload->getError() . "！');</script>";
        } else {
            //获取具体的路径，用于返回给编辑器
            $service_url =  $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].__ROOT__."/";
            $savepath = $service_url."Public/Uploads/".$info['savepath'].$info['savename'];
            //下面的输出，会自动的将上传成功的文件路径，返回给编辑器。
            echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction('".$_GET['CKEditorFuncNum']."','$savepath','上传成功');</script>";
        }
    }
    function test($index = 0){
        $cityViewModel = D("CityView");
        $orderList = $cityViewModel->where(array("id"=>$index))->find();
        $pdf = new PdfService();
        $pdf->outOrderPDF($orderList,"I");
    }
}