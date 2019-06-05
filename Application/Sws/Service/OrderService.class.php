<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 * 主要用於管理員操作訂單
 */
namespace Sws\Service;
class OrderService{

    public $order_id;//訂單id
    public $sta_id;//訂單詳情id
    public $order_list;//訂單信息
    public $city_auth ="";//用戶的城市權限
    public $html ="";//過期訂單發送郵件專用

    public $one_html="";//一般害蟲的html（管理員專用)
    public $two_html="";//特殊害蟲的html（管理員專用)

    public $this_add_html="";//混合訂單時需含有普通訂單html
    public $this_add_sta_id="";//混合訂單時普通業務的id

    public $order_code;//後續修改添加變量（新訂單使用)

    //完善訂單（訂單添加后自動完成害蟲)
    public function addOrderBusList($orderId,$businessList){
        $define = C('DEFINE');
        $this->order_id = $orderId;
        $orderBusModel = D("OrderBus");
        $orderStaModel = D("OrderSta");
        $orderHisModel = D("OrderHis");
        $orderViewModel = D("OrderView");
        $orderBusViewModel = D("OrderBusView");
        $businessModel = D("Business");
        $orderList = $orderViewModel->where(array("id"=>$orderId))->find();
        foreach ($businessList as $business_id){
            $business = $businessModel->where(array("id"=>$business_id))->find();
            $orderBusModel->create(array(
                "order_id"=>$orderId,
                "bus_type"=>$business["type"],
                "bus_id"=>$business_id
            ));
            $orderBusModel->add();//害蟲添加
        }
        $orderList["house_type_ns"] = L($define["house_type"][$orderList["house_type"]]);
        $orderList["appellation_ns"] = L($define["appellation_list"][$orderList["appellation"]]);
        $orderList["b_unit_ns"] = L($orderList["b_unit"]);
        $orderList["status"] = 'send';
        $businessList_only = $orderBusViewModel->where(array("order_id"=>$orderId,"type"=>1))->select();//此處可以優化、不需要查詢
        $orderList["business_list"] = $businessList_only;
        setOrderTotalPrice($orderList);//計算總價
        $businessList_only = $orderList["business_list"];
        $this->order_list = $orderList;
        $orderCode = $orderList["order_code"];

        //根據害蟲類型是否生成多個訂單（開始)
        $source_order_code = $orderList["order_code"];
        $this->order_code = $orderList["order_code"];
        $type = intval($orderList["order_type"]);
        if($type === 1 || $type === 2){//普通業務及混合業務
            $orderList["order_code"]=$source_order_code."A";
            $orderStaModel->create(array(
                "order_id"=>$orderId,
                "s_code"=>$orderList["order_code"],
                "s_type"=>1,
            ));
            $sta_id = $orderStaModel->add();
            $this->this_add_sta_id = $sta_id;
            $orderHisModel->create(array(
                "sta_id"=>$sta_id,
                "status"=>"new",
            ));
            $orderHisModel->add();//記錄操作
            //給管理員發送郵件
            $this->sendEmailToAdmin(1,$sta_id);
            $this->sta_id = $sta_id;
        }
        if($type === 0 || $type === 2){//特殊業務及混合業務
            $businessList = $orderBusViewModel->where(array("order_id"=>$orderId,"type"=>0))->select();//此處可以優化、不需要查詢
            $orderList["business_list"] = $businessList;
            $this->order_list = $orderList;
            $orderList["order_code"]=$source_order_code."B";
            $orderStaModel->create(array(
                "order_id"=>$orderId,
                "s_code"=>$orderList["order_code"],
                "s_type"=>0,
            ));
            $sta_id = $orderStaModel->add();
            $orderHisModel->create(array(
                "sta_id"=>$sta_id,
                "status"=>"new",
            ));
            $orderHisModel->add();//記錄操作
            //給管理員發送郵件
            $this->sendEmailToAdmin(0,$sta_id);
        }
        if($type !== 0){
            $orderList["order_code"] = $orderCode."A";
        }
        if(is_array($businessList_only)&&count($businessList_only)>0){
            $orderList["business_list"] = $businessList_only;
        }
        $this->order_list = $orderList;
        //根據害蟲類型是否生成多個訂單（結束)
    }

    //新訂單生成后給管理員發送郵件
    private function sendEmailToAdmin($type,$sta_id){
        $prefix = getNamePrefix();
        $orderList = $this->order_list;
        $this->order_list["order_type"] = $type;
        $html = "";
        $addHtml = "";
        if($type===0){
            $this->order_list["order_code"] = $this->order_code."B";
            if(!empty($this->this_add_html)){
                $addHtml.="<br><p>-------------------------------------------------------------</p><br>";
                $addHtml.=$this->this_add_html;
                $url = U("/sws/order/detail",array("index"=>$this->this_add_sta_id));
                $url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$url;
                $addHtml .= '<p><a target="_blank" style="color: #337ab7;margin-right:20px; " href="'.$url.'">'.L('btn_read_email').'</a></p>';
            }
            $prefixEmali = L("order_status_sales");
        }else{
            $this->order_list["order_code"] = $this->order_code."A";
            $prefixEmali = L("email_39");
        }
        $html .= $this->printHtmlToBusiness();
        $this->order_list["order_type"] = $orderList["order_type"];
        $this->order_list["order_code"] = $orderList["order_code"];
        $bus_str = "";
        foreach ($orderList["business_list"] as $business) {
            if($business["type"] == $type){
                $bus_str.=$business["name".$prefix]."、";
            }
        }
        if($prefix == "_us"){
            $admin_title = $prefixEmali." - ".$orderList["appellation_ns"]." ".$orderList["order_name"]." - ".$bus_str;
        }else{
            $admin_title = $prefixEmali." - ".$orderList["order_name"].$orderList["appellation_ns"]." - ".$bus_str;
        }
        $email = new EmailService("",$admin_title,$html,$type);
        $email->setAdminToEmail($orderList["city_id"],$sta_id);
        $email->addHtmlToContent($addHtml);
        $email->sendMail();
    }

    //根據業務輸出不同的HTML
    public function printHtmlToBusiness(){
        $orderList = $this->order_list;

        $prefix = getNamePrefix();
        if(empty($orderList["order_type"])) {
            //setOrderTotalPrice($orderList);//計算總價
            //特殊業務
            $html="";
            $html.="<p>".L("order_code")."：".$orderList["order_code"]."</p>";
            $html.="<p>".L("user_name")."：".$orderList["order_name"]."</p>";//user_name
            $html.="<p>".L("appellation")."：".$orderList["appellation_ns"]."</p>";//appellation
            $html.="<p>".L("tel")."：".$orderList["phone"]."</p>";//tel
            $html.="<p>".L("email")."：".$orderList["email"]."</p>";//email
            $html.="<p>".L("house_type")."：".$orderList["house_type_ns"]."</p>";//house_type
            $html.="<p>".L("city_address")."：".$orderList["city_name".$prefix]." - ".$orderList["area_name".$prefix]."</p>";//city_address
            $html.="<p>".L("address")."：".$orderList["address"]."</p>";//address
            $html.="<p>".L("business_name_none")."：";

            foreach ($orderList["business_list"] as $business) {
                if($business["type"] == 0){
                    $html.=$business["name".$prefix]."、";
                }
            }
            $html.="</p>";
        }else{
            $html = $this->getOrderHtmlToOrderId();
            $this->this_add_html = $html;
        }
        return $html;
    }

    //獲取訂單的html(沒有PDF的一般業務)
    public function getOrderHtmlToOrderId($bool = true){
        $orderList = $this->order_list;
        if(!key_exists("kehu_lang",$orderList)){
            $orderList["kehu_lang"] = "";
        }
        $prefix = getNamePrefix($orderList["kehu_lang"]);
        $unit = L($orderList["b_unit"]);
        $currencyType = $orderList["currency_type"];
        setOrderTotalPrice($orderList);//計算總價
        $html="";
        $html.="<p>".L("order_code")."：".$orderList["order_code"]."</p>";
        $html.="<p>".L("user_name")."：".$orderList["order_name"]."</p>";
        $html.="<p>".L("order_phone")."：".$orderList["phone"]."</p>";
        $html.="<p>".L("order_email")."：".$orderList["email"]."</p>";
        $html.="<p>".L("city_address")."：".$orderList["city_name".$prefix]." - ".$orderList["area_name".$prefix]."</p>";
        $html.="<p>".L("address")."：".$orderList["address"]."</p>";
        if($bool){
            $html.="<p>".L("car_fare")."：".$orderList["area_price"]."（".$currencyType."）</p>";
        }
        if(!empty($orderList["door_in"])){
            if(strtolower($orderList["web_prefix"])=='cn'){
                $html.="<p>".L("Indoor_area")."：".$orderList["door_in"]."（".$unit."）</p>";
            }else{
                $html.="<p>".L("Indoor_area_a")."：".$orderList["door_in"]."（".$unit."）</p>";
            }
        }
        $html.="<p>".L("application")."：".$orderList["number"]."</p>";
        if(!empty($orderList["service_time"])){
            $html.="<p>".L("service_time")."：".$orderList["service_time"].L("to").$orderList["service_time_end"]."</p>";
        }
        $voDoor = intval($orderList["door_in"])+intval($orderList["door_out"]);
        if($bool){
            $html .= '<table border="1"><thead><tr><td>'.L("business_name").'</td><td>'.L("a")."".$unit."".L("price").'（'.$currencyType.'）</td><td>'.L("business_min_price").'（'.$currencyType.'）</td><td>'.L("total_price").'（'.$currencyType.'）</td></tr></thead><tbody>';

            $minPrice = floatval($orderList["min_price"]);
            foreach ($orderList["business_list"] as $business) {
                if($business["type"] == 1){ //一般業務
                    $thisSum = $voDoor * floatval($business["price"]);
                    if($orderList["calculation"]==1){ //合併計算
                        $thisSum = sprintf("%.2f", $thisSum);
                    }else{
                        $thisSum = $thisSum<$minPrice?$minPrice:$thisSum;
                        $thisSum = sprintf("%.2f", $thisSum);
                    }
                    $html.="<tr><td>".$business["name".$prefix]."</td><td>".$business["price"]."</td><td>".$minPrice."</td><td>".$thisSum."</td></tr>";
                }
            }
            $html.='</tbody><tfoot><tr><td colspan="3"></td><td>'.sprintf("%.2f", $orderList["total_price"]).'</td></tr></tfoot></table>';
        }

        return $html;
    }

    //獲取新郵箱的html（客戶）
    public function getOrderHtmlToKeHu($kehu_lang=""){
        $orderList = $this->order_list;
        $sta_id = $this->sta_id;
        $kehu_lang = empty($kehu_lang)?LANG_SET:$kehu_lang;
        $prefix = getNamePrefix($kehu_lang);
        $html="";
        if(empty($orderList["order_type"])){
            //特殊業務
 /*         $html="<div style='display: table;width: 700px;margin: 0 auto;'>";
            $html.="<p style='text-align: center;'><img src='cid:titleLog'/></p>";
            $html.="<p style='text-align: center;'>This is an automated reply</p>";*/
            if($prefix == "_us"){
                $html.="<p style='margin: 10px 0px;'>".$orderList['appellation_ns'].'  '.$orderList['order_name']."</p>";
            }else{
                $html.="<p style='margin: 10px 0px;'>".$orderList['order_name'].'  '.$orderList['appellation_ns']."</p>";
            }
            if($orderList["region_name"]=="中国"){
                $html.="<p>".L("email_9_1")."</p>";
            }else{
                $html.="<p>".L("email_9_2")."</p>";
            }
            if(strtolower($kehu_lang) == 'en-us'){
                $html.="<p>".L("email_25")."</p>";
                $html.="<p>".L("limited")."</p>";
            }else{
                $html.="<p>".L("limited")."</p>";
                $html.="<p>".L("email_25")."</p>";
            }
            //$html.="<p>&nbsp;</p>";
            //$html.="<p>".L("tel").":(852)3575 2575   ".L("Fax").":(852)3575 2570  ".L("email").":<a href='info@biocycle.hk' target='_blank'>info@biocycle.hk</a>  ".L("Website").":<a href='http://www.biocycle.hk' target='_blank'>http://www.biocycle.hk</a></p>";
            //$html.="<p>".L("email_12")."</p>";
            //$html.="</div>";
        }else{
            //普通業務
            $url_a = U("/sws/login/editOrder",array("index"=>$sta_id,"token"=>$orderList['token'],"type"=>"a"));
            $url_b = U("/sws/login/editOrder",array("index"=>$sta_id,"token"=>$orderList['token'],"type"=>"b"));
            $url_c = U("/sws/login/editOrder",array("index"=>$sta_id,"token"=>$orderList['token'],"type"=>"c"));
            $url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"];
            if($prefix == "_us"){
                $html='<p>'.$orderList['appellation_ns'].'  '.$orderList['order_name'].'</p>';
            }else{
                $html='<p>'.$orderList['order_name'].'  '.$orderList['appellation_ns'].'</p>';
            }
            $html.='<p>'.L("email_14").'</p>';
            $html.='<p>'.L("email_15").'</p>';
            $html.='<p>'.L("email_16").'</p>';

            if($orderList["order_type"] == 2){ //混合業務
                if($orderList["region_name"]=="中国"){
                    $html.='<p>'.L("email_hybrid_1").''.L("thank_you").'</p>';
                }else{
                    $html.='<p>'.L("email_hybrid_2").''.L("thank_you").'</p>';
                }
            }
            $html.='<p>————————————————————</p>';
            $html.='<p>'.L("email_17").'</p>';
            $html.='<p style="margin: 0px;">'.L("email_18_1").'<a href="'.$url.''.$url_a.'">'.L("email_18").'</a></p>';
            $html.='<p style="margin: 0px;">'.L("email_19_1").'<a href="'.$url.''.$url_b.'">'.L("email_19").'</a></p>';
            $html.='<p style="margin: 0px;">'.L("email_21_1").'<a href="'.$url.''.$url_c.'">'.L("email_21").'</a></p>';

            if(strtolower($kehu_lang) == 'en-us'){
                $html.='<p>'.L("email_25").'<br>'.L("email_sws").'</p>';
            }else{
                $html.='<p>'.L("email_sws").' '.L("email_25").'</p>';
            }
        }
        return $html;
    }

    //獲取訂單詳情（客戶打開）
    public function getOrderNow($order_id,$token){
        $orderViewModel = D("OrderView");
        $orderBusViewModel = D("OrderBusView");
        $orderList = $orderViewModel->where(array("id"=>$order_id,"token"=>$token))->find();
        if($orderList){
            $web_prefix = getWebPrefix();//域名尾綴（後期刪除）（重新添加）
            $prefix = getNamePrefix();
            $regionModel = D("Region");
            $cityModel = D("City");
            $areaModel = D("Area");
            $businessModel = D("Business");
            $businessList = $orderBusViewModel->where(array("order_id"=>$order_id))->select();
            $orderList["business_list"] = $businessList;
            $arr = array();
            foreach ($businessList as $list){
                array_push($arr,$list["bus_id"]);
            }
            $orderList["business_id"] = $arr;
            $businessList = $businessModel->where(array("city_id"=>$orderList["city_id"]))->getField('id,name'.$prefix);
            $regionList = $regionModel->where(array("www_fix"=>array('like',"%|$web_prefix|%")))->order('z_index asc')->getField('id,region_name'.$prefix);
            //$regionList = $regionModel->order('z_index asc')->getField('id,region_name'.$prefix);
            $cityList = $cityModel->where(array('region_id'=>$orderList["region_id"]))->getField("id,city_name".$prefix);
            $areaList = $areaModel->where(array('city_id'=>$orderList["city_id"]))->getField("id,area_name".$prefix);
            $areaList[0]=L("city_other");//pestcontrol/index.php?lang=en
            $url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"]."/".C('DEFINE')["close_open_prefix"]."/index.php?lang=".getTopStrToLang();
            $closeUrl = $url;
            $email_import = getEmailImportToEmail($orderList["email"]);
        }else{
            return array();
        }
        return array(
            "regionList"=>$regionList,
            "cityList"=>$cityList,
            "areaList"=>$areaList,
            "orderList"=>$orderList,
            "closeUrl"=>$closeUrl,
            "email_import"=>$email_import,
            "businessList"=>$businessList
        );
    }

    //獲取所有的訂單列表(有權限）
    public function getOrderAllListToCity(){
        $define = C('DEFINE');
        $user = session("user");
        $prefix = getNamePrefix();
        $orderStaViewModel = D("OrderStaView");
        $orderBusViewModel = D("OrderBusView");
        $nowDate = date("Y-m-d");
        $first_day = date('Y-m-d 00:00:00', strtotime("$nowDate -1 month"));
        $map["city_id"]= array('in',$user["city_auth"]);
        $map["lcd"]= array('EGT',$first_day);
        $orderList = $orderStaViewModel->where($map)->order('id desc')->select();
        foreach ($orderList as &$order){
            $order["from_order"] = empty($order["from_order"])?L("PC"):L("weChat");
            $order["appellation_ns"] = L($define["appellation_list"][$order["appellation"]]);
            $businessList = $orderBusViewModel->where(array("order_id"=>$order["order_id"],"type"=>$order["s_type"]))->getField('name'.$prefix,true);
            $order["business_name"] = implode("、",$businessList);
            $order["style"] = getStyleToStatus($order["status"]);
            $order["lcd"] = date("Y-m-d",strtotime($order["lcd"]));
            if($order["s_type"]==0 && $order["status"]=="send"){
                $order["status"] = "order_status_sales";
			}elseif ($order["s_type"]==1 && $order["status"]=="send"){
				$order["status"] = "send_new";
            }
        }
        return $orderList;
    }

    //根據狀態id獲取訂單所有信息(有權限）
    public function getOrderListToId($staId){
        $define = C('DEFINE');
        $user = session("user");
        $orderStaViewModel = D("OrderStaView");
        $orderBusViewModel = D("OrderBusView");
        $map["city_id"]= array('in',$user["city_auth"]);
        $map["id"]= $staId;
        $orderList = $orderStaViewModel->where($map)->find();
        if($orderList){
            $orderId = $orderList["order_id"];
            $orderList["house_type_ns"] = L($define["house_type"][$orderList["house_type"]]);
            $orderList["appellation_ns"] = L($define["appellation_list"][$orderList["appellation"]]);
            $orderList["b_unit_ns"] = L($orderList["b_unit"]);
            $orderList["style"] = getStyleToStatus($orderList["status"]);
            if($orderList["s_type"]==0 && $orderList["status"]=="send"){
                $orderList["status"] = "order_status_sales";
            }
            $businessList = $orderBusViewModel->where(array("order_id"=>$orderId,"type"=>$orderList["s_type"]))->select();
            $orderList["business_list"] = $businessList;
            setOrderTotalPrice($orderList);
            $totalPrice = $orderList['total_price'];
            $totalPrice = $orderList["s_type"] == 1?$totalPrice:0;
            $orderList['total_price'] = sprintf("%.2f", $totalPrice);
            return $orderList;
        }else{
            return false;
        }
    }

    //修改訂單的害蟲列表、價格
    public function updateOrderBusList($data,$rs){
        // 切換语言包
        if(in_array($rs["kehu_lang"],array("zh-cn","zh-tw","en-us"))){
            $file   =   MODULE_PATH.'Lang/'.$rs["kehu_lang"].'.php';
            if(is_file($file))
                L(include $file);
        }
        $define = C('DEFINE');
        $businessModel = D("Business");//害蟲表
        $orderBusModel = D("OrderBus");//訂單害蟲列表
        $orderStaModel = D("OrderSta");//訂單狀態、價格表
        $orderHisModel = D("OrderHis");//訂單記錄表
        $orderViewModel = D("OrderView");//訂單視圖（不包括害蟲、狀態）
        $orderBusModel->where(array("order_id"=>$data["id"],"bus_type"=>$rs["s_type"]))->delete();//清空訂單的害蟲表
        $orderList = $orderViewModel->where(array("id"=>$data["id"]))->find();
        $orderList["house_type_ns"] = L($define["house_type"][$orderList["house_type"]]);
        $orderList["appellation_ns"] = L($define["appellation_list"][$orderList["appellation"]]);
        $orderList["b_unit_ns"] = L($orderList["b_unit"]);
        $orderList["service_time"] = $data["service_time"];
        $orderList["service_time_end"] = $data["service_time_end"];
        $orderList["total_price"] = $data["total_price"];
        $orderList["order_code"] = $rs["s_code"];
        $orderList["status"] = 'modified';
        $hisMap['status']  = 'ok_order';
        $hisMap['sta_id']  = $rs["id"];
        $determine = $orderHisModel->where($hisMap)->order('status desc')->find();
        if($determine){
            $orderList["determine"] = $determine["lcd"];
        }else{
            $orderList["determine"] = date("Y-m-d H:i:s");
        }
        $orderList["order_type"] = $rs["s_type"];//因為是管理員修改、所以訂單只能為一種類型
        $orderList["business_list"] = array();
        foreach ($data["business_id"] as $business_id){
            $business = $businessModel->where(array("id"=>$business_id))->find();
            if($business){
                $orderList["business_list"][] = $business;
                $orderBusModel->create(array(
                    "order_id"=>$rs["order_id"],
                    "bus_type"=>$business["type"],
                    "bus_id"=>$business_id
                ));
                $orderBusModel->add();//害蟲添加
            }
        }
        $data["service_time"] = empty($data["service_time"])?null:$data["service_time"];
        $data["service_time_end"] = empty($data["service_time_end"])?null:$data["service_time_end"];
        $orderStaList = array(
            'total_price'=>$data["total_price"],
            'service_time'=>$data["service_time"],
            'service_time_end'=>$data["service_time_end"],
            'status'=>$orderList["status"],
            'kehu_set'=>0,
            'id'=>$rs["id"]
        );
        $orderStaModel->create($orderStaList,2);
        $orderStaModel->save();//訂單狀態、價格修改
        $orderHisModel->create(array(
            "sta_id"=>$rs["id"],
            "status"=>"update",
        ));
        $orderHisModel->add();//記錄操作

        $this->order_id = $data["id"];
        $this->sta_id = $rs["id"];
        $this->order_list = $orderList;
        $this->sendTemplate($rs["id"],"您的订单已修改，请及时核对信息！");
    }

    protected function sendTemplate($sta_id,$change_title){
        if(!empty($sta_id)){
            $orderList = $this->order_list;
            $orderWechatModel = D("OrderWechat");
            $openid = $orderWechatModel->where(array("order_sta_id"=>$sta_id))->getField("openid");
            if($openid){//如果綁定了微信，發送微信模板信息
                $arrName = array();
                foreach ($orderList["business_list"] as $list){
                    $arrName[] = $list["name"];
                }
                $weChatService = new WeChatService();
                $data = array(
                    "change_title"=>$change_title,
                    "sta_id"=>$sta_id,
                    "order_name"=>$orderList["order_name"],
                    "s_code"=>$orderList["order_code"],
                    "name"=>$arrName,//害蟲名字
                    "lcd"=>$orderList["lcd"],
                );
                $weChatService->sendTemplateToOrderChange($data,$openid);
            }
        }

    }

    //訂單處理(訂單接受、拒絕)、發郵箱給客戶
    public function changeStatus($sta_id,$status){
        $oldLang = LANG_SET;
        $pdfBool = false;//郵件是否帶有pdf
        $user = session("user");
        $orderStaViewModel = D("OrderStaView");
        $orderStaModel = D("OrderSta");//訂單狀態、價格表
        $orderHisModel = D("OrderHis");//訂單記錄表
        $orderModel = D("Order");//訂單記錄表
        $map["id"] = $sta_id;
        $map["city_id"]= array('in',$user["city_auth"]);
        $orderList = $orderStaViewModel->where($map)->find();
        $hisMap['status']  = 'ok_order';
        $hisMap['sta_id']  = $orderList["id"];
        $determine = $orderHisModel->where($hisMap)->order('status desc')->find();
        if($determine){
            $orderList["determine"] = $determine["lcd"];
        }else{
            $orderList["determine"] = date("Y-m-d H:i:s");
        }
        if($orderList){
            // 切換语言包
            if(in_array($orderList["kehu_lang"],array("zh-cn","zh-tw","en-us"))){
                $file   =   MODULE_PATH.'Lang/'.$orderList["kehu_lang"].'.php';
                if(is_file($file))
                    L(include $file);
            }
            $orderList["order_code"] = $orderList["s_code"];
            $data = array("id"=>$sta_id,"status"=>$status,"kehu_set"=>1);
            switch ($status){
                case "service":
                    $total_price = I("total_price","");//總價
                    $address = I("address","");//詳細地址
                    $service_time = I("service_time","");//服務時間
                    $service_time_end = I("service_time_end","");//服務時間
                    $orderModel-> where(array("id"=>$orderList["order_id"]))->setField('address',$address);
                    $orderList["address"] = $address;
                    $orderList["service_time"] = $service_time;
                    $orderList["service_time_end"] = $service_time_end;
                    $orderList["total_price"] = $total_price;
                    $orderList["determine"] = date("Y-m-d H:i:s");
                    $orderList["status"] = "service";
                    $data["service_time"]=$service_time;
                    $data["service_time_end"]=$service_time_end;
                    $data["total_price"]=$total_price;
                    $email_title = L("email_42");
                    $his_data = array("sta_id"=>$sta_id,"status"=>"ok_order");
                    $template_title = "您的订单已受理！";
                    break;
                case "finish":
                    $total_price = I("total_price","");//總價
                    $address = I("address","");//詳細地址
                    $service_time = I("service_time","");//服務時間
                    $service_time_end = I("service_time_end","");//服務時間
                    $orderModel-> where(array("id"=>$orderList["order_id"]))->setField('address',$address);
                    $orderList["address"] = $address;
                    $orderList["service_time"] = $service_time;
                    $orderList["service_time_end"] = $service_time_end;
                    $orderList["total_price"] = $total_price;
                    $orderList["status"] = "finish";
                    $data["service_time"]=$service_time;
                    $data["service_time_end"]=$service_time_end;
                    $data["total_price"]=$total_price;
                    $email_title = L("email_42");
                    $his_data = array("sta_id"=>$sta_id,"status"=>"finish");
                    $end_html = "";
                    $template_title = "您的订单已完成！";
                    break;
                case "reject":
                    $remark = I("remark");//拒絕原因
                    $email_title = L("email_7");
                    $data["remark"]=$remark;
                    $his_data = array("sta_id"=>$sta_id,"status"=>"no_order");
                    $end_html='<p>'.L("reject_remark").':'.$remark.'</p>';
                    $template_title = "您的订单被拒绝！";
                    break;
                default:
                    return false;
            }
            $orderStaModel->create($data,2);
            $orderStaModel->save();//訂單狀態、價格修改
            $orderHisModel->create($his_data);
            $orderHisModel->add();//記錄操作
            $this->order_id = $orderList["order_id"];
            $this->order_list = $orderList;
            $this->resetOrderListNs();
            if(in_array($status,array("service","finish"))){ //2018-05-05 新需求
                $html = $this->getFinishHtml();
            }else{
                $html = $this->getOrderHtmlToOrderId(false);
            }
            if($orderList["s_type"] == 1 && $status != "reject"){
                //如果是一般害蟲pdf重做
                $pdfBool = true;
                $pdf = new PdfService();
                $pdf->outOrderPDF($this->order_list,"F",$orderList["kehu_lang"]);
            }
            if($status != "reject"){  //拒絕訂單不發送郵件
                sendMail($orderList["email"],$email_title,$html.$end_html,$orderList["s_code"],$pdfBool);
            }
            // 切換语言包
            if(!empty($oldLang)){
                $file   =   MODULE_PATH.'Lang/'.$oldLang.'.php';
                if(is_file($file))
                    L(include $file);
            }
            $this->sendTemplate($sta_id,$template_title);
            return true;
        }else{
            return false;
        }
    }
    //2018-05-05 新需求
    public function getFinishHtml(){
        $orderList = $this->order_list;
        $define = C('DEFINE');
        if(!key_exists("kehu_lang",$orderList)){
            $orderList["kehu_lang"] = "";
        }
        $prefix = getNamePrefix($orderList["kehu_lang"]);
        $orderList["appellation_ns"] = L($define["appellation_list"][$orderList["appellation"]]);
        $html="";
        if($prefix == "_us"){
            $html.="<p>".$orderList["appellation_ns"]." ".$orderList["order_name"]."</p>";
        }else{
            $html.="<p>".$orderList["order_name"]." ".$orderList["appellation_ns"]."，</p>";
        }
        $html.="<p>".L("finish_email_1")."</p>";

        if($prefix == "_us"){
            $html.="<p>".L("email_25").'<br>'.L("email_26")."</p>";
        }else{
            $html.="<p>".L("email_26").'&nbsp;'.L("email_25")."</p>";
        }

        return $html;
    }

    //訂單退回（不發送郵件)
    public function backOrder($sta_id){
        $user = session("user");
        $orderStaViewModel = D("OrderStaView");
        $orderStaModel = D("OrderSta");//訂單狀態、價格表
        $orderHisModel = D("OrderHis");//訂單記錄表
        $map["id"] = $sta_id;
        $map["city_id"]= array('in',$user["city_auth"]);
        $orderList = $orderStaViewModel->where($map)->find();
        if($orderList){
            $orderList["order_code"] = $orderList["s_code"];
            $data = array("id"=>$sta_id,"status"=>"send","kehu_set"=>1);
            $his_data = array("sta_id"=>$sta_id,"status"=>"order_back");
            $orderStaModel->create($data,2);
            $orderStaModel->save();//訂單退回
            $orderHisModel->create($his_data);
            $orderHisModel->add();//記錄操作
            return true;
        }else{
            return false;
        }
    }

    //補充訂單的後續信息（害蟲、語言）
    private function resetOrderListNs(){
        $define = C('DEFINE');
        $orderId = $this->order_id;
        $orderList = $this->order_list;
        $orderBusViewModel = D("OrderBusView");
        $map["order_id"] = $orderId;
        if(key_exists("s_type",$orderList)){
            $map["type"] = $orderList["s_type"];
        }
        $businessList = $orderBusViewModel->where($map)->select();
        if(empty($orderList["area_id"])){ //其它區域價格設定
            $orderList["area_price"] = $orderList["other_price"];
            $orderList["min_price"] = $orderList["other_min"];
        }
        $orderList["house_type_ns"] = L($define["house_type"][$orderList["house_type"]]);
        $orderList["appellation_ns"] = L($define["appellation_list"][$orderList["appellation"]]);
        $orderList["b_unit_ns"] = L($orderList["b_unit"]);
        $orderList["business_list"] = $businessList;
        $this->order_list = $orderList;
    }

    //修改過期訂單
    public function scheduleStatus(){
        $orderStaModel = D("OrderSta");
        $orderStaViewModel = D("OrderStaView");
        $orderHisModel = D("OrderHis");
        $nowDate = date("Y-m-d H:i:s");
        $first_day = date('Y-m-d 00:00:00', strtotime("$nowDate -7 day"));
        $end_day = date('Y-m-d 23:59:59', strtotime("$nowDate -5 day"));
        $updateMap["lcd"]=array('LT',$first_day);
        $updateMap["s_type"]=1;
        $updateMap["status"]=array('not in',array("finish","reject","auto_rejected","service"));
        $map["lcd"]=array(array('EGT',$first_day),array('ELT',$end_day));
        $map["s_type"]=1;
        $map["send_email"] = 0;
        $map["status"] = array('not in',array("finish","reject","auto_rejected","service"));

        $data = array('status'=>'auto_rejected','remark'=>'自动过期','luu'=>'自动过期');
        $order_list = $orderStaModel->where($updateMap)->select();
        foreach ($order_list as $order){
            //記錄操作開始(自動過期）
            $his_data = array("sta_id"=>$order["id"],"status"=>"auto_rejected");
            $orderHisModel->create($his_data);
            $orderHisModel->add();
            //記錄操作結束
        }
        $orderStaModel->where($updateMap)->setField($data);//自動過期
        //報價單有效期限前２天以電郵方式提示地區的訂單管理人
        $order_list = $orderStaViewModel->where($map)->select();
        $arr=array();
        $orderStaViewModel->where($map)->setField('send_email',1);
        //整理即將過期的訂單（按城市為主鍵的二維數組）
        foreach ($order_list as $order){
            $arr[$order["city_id"]][] = $order;
            //記錄操作開始(郵件發送記錄）
            $his_data = array("sta_id"=>$order["id"],"status"=>"overdue_email");
            $orderHisModel->create($his_data);
            $orderHisModel->add();
            //記錄操作結束
        }
        $this->order_list = $arr;
    }

    //訂單將要過期時發送郵箱提示管理員
    public function scheduleTask($user){
        $city_auth = $user["city_auth"];
        $userLang = $user["lang"];
        $prefix = getNamePrefixToStr($userLang);
        //切換語言包
        if(in_array($userLang,array("zh-cn","zh-tw","en-us"))){
            $file   =   MODULE_PATH.'Lang/'.$userLang.'.php';
            if(is_file($file))
                L(include $file);
        }
        $orderList = $this->order_list;
        //$cityModel->where(array("id"=>0))->delete();
        if(!empty($orderList)&&!empty($city_auth)){
            $cityList = explode(",",$city_auth);
            $key = 0;
            $html = "";
            foreach ($cityList as $city){
                if (empty($city)){
                    continue;
                }
                if(array_key_exists($city,$orderList)){
                    if($key === 0){
                        $html = L("order_alert");
                        $html .="<table width='900px' border='1px' style='border: 1px solid #000;'>";
                        $html .= L("order_alert_table");
                    }
                    foreach ($orderList[$city] as $order){
                        $url = U("/sws/order/detail",array("index"=>$order["id"]));
                        $url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$url;
                        $html.="<tr align='left'>";
                        $html.="<td>".$order["s_code"]."</td>";
                        $html.="<td>".$order["order_name"]."</td>";
                        $html.="<td>".$order["phone"]."</td>";
                        $html.="<td>".$order["city_name$prefix"]."</td>";
                        $html.="<td>".$order["region_name$prefix"]."</td>";
                        $html.="<td>".date("Y-m-d",strtotime($order["lcd"]))."</td>";
                        $html.="<td><a target='_blank' href='$url'>".L("view")."</a></td>";
                        $html.="</tr>";
                    }
                    $key++;
                }
            }
            if(!empty($html)){
                $html.="</tbody></table>";
            }
            $this->html = $html;
        }else{
            $this->html = "";
        }
        return $this->html;
    }

}