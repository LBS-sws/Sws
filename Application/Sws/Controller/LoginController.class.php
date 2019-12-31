<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Service\EmailService;
use Sws\Service\OrderService;
use Sws\Service\PdfService;
use Sws\Service\PhpWordService;
use Sws\Service\PhpWordServiceTwo;
use Think\Controller;

class LoginController extends Controller {
    public function index(){
        if(session('?user')){
            $this->redirect('/sws/index/index');
        }else{
            layout(false);
            $url = $_GET["login"];
            $url = strtr($url,"唷","/");
            if(!empty($url)){
                $this->assign("oldUrl",base64_decode($url));
            }else{
                $this->assign("oldUrl",U('/sws/index/index'));
            }
            $this->display();
        }
	}

    public function loginAjax(){
        if(IS_AJAX){
            $userModel = D("User");
            $userView = D("UserView");
            $map["user_name"] = I("username");
            $userList = $userModel->where($map)->find();
            if($userList){
                $password = setMD5Pwd(I("password"));
                if($password === $userList["password"]){
                    $user = $userView->where(array("id"=>$userList["id"]))->find();
                    session('user',$user);
                    $this->ajaxReturn(array("status"=>1));
                }else{
                    $this->ajaxReturn(array("status"=>0,"error"=>"password","content"=>L("error_password")));
                }
            }else{
                $this->ajaxReturn(array("status"=>0,"error"=>"username","content"=>L("error_username")));
            }
        }else{
            $this->error(L("illegal_request"),"/sws/login/index",5);
        }
	}

    public function changeCity(){
        if(IS_AJAX){
            $prefix = getNamePrefix();
            $web_prefix = getWebPrefix();//域名尾綴（後期刪除）（重新添加）
            $cityViewModel = D("CityView");
            $cityModel = D("City");
            $areaModel = D("Area");
            $businessModel = D("Business");
            $city_id = I("city_id");
			$re_city = $city_id;
            if(empty($city_id)){
                $cityViewList = $cityViewModel->where(array("www_fix"=>array('like',"%|$web_prefix|%")))->order('z_index asc')->find();
                if($cityViewList){
                    $city_id = $cityViewList["id"];
                }
            }
            $cityList = $cityModel->where(array("id"=>$city_id))->order('z_index asc')->find();
            if($cityList){
                if(empty($re_city)){
                    $cityList["other_open"] = 0;
                }
                $areaList = $areaModel->where(array("city_id"=>I("city_id"),"id"=>array("neq","0")))->order('z_index asc')->select();
                $businessList = $businessModel->where(array("city_id"=>$city_id))->select();
                $this->ajaxReturn(array("status"=>1,"areaList"=>$areaList,"businessList"=>$businessList,"otherBool"=>$cityList["other_open"],"prefix"=>$prefix,"unit"=>L($cityList['b_unit'])));
            }else{
                $businessList = array(
                    array("id"=>"","name"=>"蟑螂","name_tw"=>"蟑螂","name_us"=>"Cockroach","type"=>1),
                    array("id"=>"","name"=>"跳蚤","name_tw"=>"跳蝨","name_us"=>"Flea","type"=>1),
                    array("id"=>"","name"=>"蚂蚁","name_tw"=>"螞蟻","name_us"=>"Ant","type"=>1),
                    array("id"=>"","name"=>"老鼠","name_tw"=>"老鼠","name_us"=>"Rodent","type"=>1),
                    array("id"=>"","name"=>"衣鱼","name_tw"=>"衣魚","name_us"=>"Silver fish","type"=>1),
                    array("id"=>"","name"=>"床蝨","name_tw"=>"床蝨","name_us"=>"Bedbug","type"=>1),
                    array("id"=>"","name"=>"白蚁","name_tw"=>"白蟻","name_us"=>"Termite","type"=>1),
                    array("id"=>"","name"=>"其他","name_tw"=>"其他","name_us"=>"Other","type"=>1),
                );
                $this->ajaxReturn(array("status"=>0,"businessList"=>$businessList,"prefix"=>$prefix));
            }
        }else{
            $this->error(L("illegal_request"),"/sws/login/order",5);
        }
	}

    //客戶下訂單
    public function order(){
        $cityModel = D("City");
        $regionModel = D("Region");
        $web_prefix = getWebPrefix();//域名尾綴（後期刪除）（重新添加）
        $prefix = getNamePrefix();
        $cityList = $cityModel->order('region_id,z_index asc')->getField('id,city_name,city_name_us,city_name_tw,currency_type,region_id');
        $regionList = $regionModel->where(array("www_fix"=>array('like',"%|$web_prefix|%")))->order('z_index asc')->getField('id,web_prefix,region_name'.$prefix);
        $this->assign("cityList",$cityList);
        $this->assign("cityName","city_name".$prefix);
        $this->assign("regionName","region_name".$prefix);
        $this->assign("regionList",$regionList);
        $this->assign("indoorList",getIndoorList());
        $this->assign("outdoorList",getOutdoorList());
        $this->assign("token",date("YmdHis").randomStrAZ());
        //__ROOT__
        layout(false);
        $this->display();
    }

    //客戶下訂單
    public function orderSave(){
        $orderModel = D("Order");
        $regionModel = D("Region");
        $data = I("post.");
        if (IS_POST){
            if (!$orderModel->create($data,1)){
                $this->error($orderModel->getError(),"/sws/login/order",5);
            }else{
                //害蟲驗證
                if(!validateBusinessList($data["business_id"])){
                    $this->error("害蟲不存在","/sws/login/order",5);
                    return false;
                }
                $result  = $orderModel->add();
                if($result !== false){
                    //訂單編號修改
                    $orderPrefix = $regionModel->where(array("id"=>$data["region"]))->getField("web_prefix");
                    $orderModel-> where(array("id"=>$result))->setField('order_code',strToCodeLength($result,$orderPrefix));
                    //訂單害蟲添加
                    $orderService = new OrderService();
                    $orderService->addOrderBusList($result,$data["business_id"]);
                    $orderList = $orderService->order_list;
                    $pdfBool = false;
                    if(!empty($orderList["order_type"])){ //非特殊業務則生成pdf文件
                        $pdfBool = true;
                        $pdf = new PdfService();
                        $pdf->outOrderPDF($orderList);
                    }
                    $html2 = $orderService->getOrderHtmlToKeHu();
                    if(sendMail($data["email"],L("email_28"),$html2,$orderList["order_code"],$pdfBool)){
                        $this->redirect("/sws/login/detail",array("index"=>$result,"token"=>$data["token"]));
                    }else{
                        $this->error(L("email_30"),"/sws/login/order",5);
                    }
                }else{
                    $this->error(L("operation_error"),"/sws/login/order",5);
                }
            }
        }else{
            $this->redirect("/sws/login/order");
        }
    }

    public function detail($index=0,$token=0){
        $orderService = new OrderService();
        $arr = $orderService->getOrderNow($index,$token);
        if(!empty($arr)){
            layout(false);
            $this->assign("orderList",$arr["orderList"]);
            $this->assign("businessList",$arr["businessList"]);
            $this->assign("regionList",$arr["regionList"]);
            $this->assign("cityList",$arr["cityList"]);
            $this->assign("areaList",$arr["areaList"]);
            $this->assign("closeUrl",$arr["closeUrl"]);
            $this->assign("email_import",$arr["email_import"]);
            $this->display("detail");
        }else{
            $this->redirect("/sws/login/order");
        }
    }

    //登錄退出
    public function loginOut(){
        session("user",null);
        $this->redirect("/sws/login/index");
    }

    //切換語言
    public function lag(){
        $lan = I("lan","zh-cn");
        $type = I("type",0);
        $lan = strtolower($lan);
        if($lan != "zh-cn" && $lan != "zh-tw" && $lan != "en-us"){
            $lan = "zh-cn";
        }
        cookie("think_language",$lan);
        if(empty($type)){
            $this->redirect("/sws/login/order");
        }else{
            $type = strtr($type,"唷","/");
            $url=base64_decode($type);
            redirect($url);
        }
    }

    //客戶修改訂單(只能修改一般業務的訂單)
    public function editOrder(){
        $type = I('type',0);//客戶的選擇
        $sta_id = I('index',0);//訂單id（order_id)
        $token = I('token',0);
        if(empty($type)||empty($sta_id)||empty($token)){
            $this->redirect("/sws/login/order");
            return false;
        }else{
            $orderStaModel = D("OrderSta");//訂單狀態、價格表
            $orderHisModel = D("OrderHis");//訂單記錄表
            $orderBusViewModel = D("OrderBusView");
            $orderStaViewModel = D("OrderStaView");
            $map["id"] = $sta_id;
            $map["s_type"] = 1;//一般業務
            $map["token"] = $token;
            $map["status"]=array("neq","finish");
            $end_day = date("Y-m-d H:i:s");
            $first_day = date('Y-m-d H:i:s', strtotime("$end_day -7 day"));
            $map["lcd"]=array(array('EGT',$first_day),array('ELT',$end_day));
            $rs = $orderStaViewModel->where($map)->find();
            if($rs){
                $file   =   MODULE_PATH.'Lang/'.$rs["kehu_lang"].'.php';
                if(is_file($file))
                    L(include $file);
            }
            switch ($type){
                case "a": //接受
                    $email_title= L("send");
                    $data = array("status"=>"guest_service","id"=>$sta_id);
                    $his_data = array("sta_id"=>$sta_id,"status"=>"ok_order");
                    break;
                case "b": //客戶要求聯繫
                    $email_title= L("email_40");
                    $data = array("status"=>"hesitate","id"=>$sta_id);
                    $his_data = array("sta_id"=>$sta_id,"status"=>"oo_order");
                    break;
                case "c": //拒絕
                    $email_title= L("email_41");
                    $data = array("status"=>"reject","id"=>$sta_id);
                    $his_data = array("sta_id"=>$sta_id,"status"=>"no_order");
                    break;
                default:
                    $type = "";
            }
            if($rs){
                if($rs["kehu_set"] == 0){ //允許客戶修改訂單
                    if(!empty($type)){
                        $data['kehu_set'] = 1;
                        $orderStaModel->create($data);
                        $orderStaModel->save();//修改訂單狀態
                        $orderHisModel->create($his_data);
                        $orderHisModel->add();//記錄操作
                        //管理員主旨修改（開始)
                        $businessList = $orderBusViewModel->where(array("order_id"=>$rs["order_id"],"type"=>$rs["s_type"]))->select();
                        $define = C('DEFINE');
                        $prefix = getNamePrefixToStr($rs["kehu_lang"]);
                        $bus_str = "";
                        foreach ($businessList as $business) {
                            $bus_str.=$business["name".$prefix]."、";
                        }
                        $appellation_ns= L($define["appellation_list"][$rs["appellation"]]);
						if($prefix == "_us"){
							$admin_title = $email_title." - ".$appellation_ns." ".$rs["order_name"]." - ".$bus_str;
						}else{
							$admin_title = $email_title." - ".$rs["order_name"].$appellation_ns." - ".$bus_str;
						}
                        //管理員主旨修改（結束)
                        $email_admin = new EmailService("",$admin_title,"",$rs["s_type"]);
                        $email_admin->setEmailHtml($rs);
                        $email_admin->setAdminToEmail($rs["city_id"],$sta_id,$type);
                        $email_admin->sendMail();//給管理員發郵件
                    }
                }else{
                    $type=L("address_11");
                }
            }else{
                $type=L("address_09");
            }
            $service = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"]."/instantquote";
            layout(false);
            $this->assign("type",$type);
            $this->assign("service",$service);
            $this->display("address");
        }
    }

    //計劃任務
    public function scheduleTask(){
        $userModel = D("User");
        $cityModel = D("City");
        $userList = $userModel->where(array("old_email"=>1))->order('city_auth desc')->select();
        $orderService = new OrderService();
        $orderService->scheduleStatus();//修改過期訂單
        //$cityModel->where(array("id"=>0))->delete();
        foreach ($userList as $user){
            if(empty($user["email"])||empty($user["city_auth"])){
                continue;
            }
            $html = $orderService->scheduleTask($user);
            if(empty($html)){
                continue;
            }else{
                $email_admin = new EmailService("",L("order_day_end"),$html);
                $email_admin->addAddress($user["email"]);
                $email_admin->sendMail();//給管理員發郵件
            }
        }
        $dir = "Public/sws/pdf";
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            if ($file != "." && $file != "..") {
                if(!is_dir("$dir/$file")){
                    @unlink("$dir/$file");
                }
            }
        }
        layout(false);
        return false;
    }
	
    public function test(){
        $bool = sendMail("1948533508@qq.com","test1222","tes11t12");
        var_dump($bool);
    }
}