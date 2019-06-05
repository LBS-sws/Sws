<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 * 主要用於管理員操作訂單
 */
namespace Sws\Service;
class OrderWeChatService{
    //保存綁定訂單
    public function saveBinding($data){
        $data["openid"] = session("access_token")["openid"];
        $arr["state"] = 1;
        $orderStaViewModel = D("OrderStaView");
        $orderWeChatModel = D("OrderWechat");
        $map["s_code"] = $data["order_code"];
        $map["order_name"] = $data["order_name"];
        $map["phone"] = $data["order_phone"];
        $saveDate = array("openid"=>$data["openid"]);
        if(empty($data["openid"])){
            $arr["state"] = 0;
            $arr["error"] = "页面异常，请刷新重试！";
            return $arr;
        }
        $rs = $orderStaViewModel->where($map)->find();
        if(!$rs){
            $arr["state"] = 0;
            $arr["error"] = "报价单不存在，请重新填写！";
            return $arr;
        }
        $saveDate["order_sta_id"] = $rs["id"];
        $rs = $orderWeChatModel->where(array("order_sta_id"=>$rs["id"]))->find();
        if($rs){
            $arr["state"] = 0;
            $arr["error"] = "报价单已被绑定，请重新填写！";
            return $arr;
        }
        if (!$orderWeChatModel->create($saveDate,1)){
            $arr["state"] = 0;
            $arr["error"] = $orderWeChatModel->getError();
            return $arr;
        }else{
            $result  = $orderWeChatModel->add();
            if(!$result){
                $arr["state"] = 0;
                $arr["error"] = "綁定失敗，系統異常！";
            }
        }
        return $arr;
    }

    //獲取用戶綁定的訂單列表
    public function getOrderList($type="",$page=1){
        $statusArr = $this->orderStatusList();
        $orderWeChatViewModel = D("OrderWechatView");
        if(!is_numeric($page)){
            $page = 1;
        }
        $page = $page<1?1:$page;
        $pageNum = 7;//一次拉取7個數據
        $startNum = ($page-1)*$pageNum;
        $condition["openid"] = session("access_token")["openid"];
        if(key_exists($type,$statusArr)){
            $condition["status"] = array("in",$statusArr[$type]);
        }
        //var_dump($condition);
        //->field('order_sta_id,s_type,order_id,s_code,door_in,s_type,status,total_price,order_name,lcd,appellation,phone,currency_type,other_price,other_min,calculation,min_price,area_price')
        $rows = $orderWeChatViewModel->where($condition)->order('lcd desc')->limit("$startNum,$pageNum")->select();
        foreach ($rows as &$orderList){
            $this->resetOrderOnlyOne($orderList);
        }
        //$this->resetOrderListSetBusToList($rows);
        //var_dump($orderWeChatViewModel->getLastSql());
        //var_dump($rows);
        //var_dump($orderWeChatViewModel->getDbError());
        return $rows;
    }

    protected function resetOrderOnlyOne(&$orderList){
        $prefix = "";
        $orderBusViewModel = D("OrderBusView");
        $orderList["status_str"] = L($orderList["status"]);
        //$orderList["lcd"] = date("Y-m-d",strtotime($orderList["lcd"]));
        $orderList["detail_href"] = U('/sws/api/editOrder',array('id'=>$orderList["order_sta_id"]));
        $orderList["businessList"] = $orderBusViewModel->where(array("order_id"=>$orderList["order_id"],"bus_type"=>$orderList["s_type"]))->select();
        //var_dump($orderBusViewModel->getLastSql());
        $orderList["businessList_str"] = "";
        if(empty($orderList["area_id"])){ //其它區域價格設定
            $orderList["area_price"] = $orderList["other_price"];
            $orderList["min_price"] = $orderList["other_min"];
        }
        $totalPrice = 0;//初始設定為交通費
        $minPrice =floatval($orderList["min_price"]); //實際的最低價格
        foreach ($orderList["businessList"] as $businessList){
            $orderList["businessList_str"] .= $businessList["name$prefix"]."、";
            if($orderList["s_type"] == 1){//特殊害蟲不做計算
                $price=floatval($businessList["price"])*floatval($orderList["door_in"]);
                if($orderList["calculation"]!=1) { //害蟲分開計算
                    $price = $price<$minPrice?$minPrice:$price;
                }
                $totalPrice+=$price;
            }
        }
        $orderList["businessList_str"] = rtrim($orderList["businessList_str"],"、");
        $totalPrice = $totalPrice<$minPrice?$minPrice:$totalPrice;
        $totalPrice += floatval($orderList["area_price"]);//交通費
        $currency_type = "（".$orderList["currency_type"]."）";
        if(empty($orderList["total_price"])){ //總價為空時自動計算價格
            $orderList["total_price"] = $totalPrice;
            if($orderList["s_type"] != 1){
                $currency_type = "";
                $orderList["total_price"] = "等待管理员报价";
            }
        }
        $orderList["total_price"] .= $currency_type;
    }

    protected function orderStatusList(){
        return array(
            "pending"=>array(//待處理的訂單狀態
                "send","no_order","ok_order","oo_order","no_order","new","update","order_back","rejected","auto_rejected","overdue_email","modified",
            ),
            "finish"=>array(//已完成的訂單狀態
                "finish"
            ),
        );
    }

    //訂單保存
    public function saveNewOrder(&$data){
        $orderModel = D("Order");
        $orderBusModel = D("OrderBus");
        $businessModel = D("Business");
        $orderStaModel = D("OrderSta");
        $cityViewModel = D("CityView");
        $orderHisModel = D("OrderHis");
        $orderWechatModel = D("OrderWechat");
        $arr["status"]=1;
        if(empty($data)){
            $arr["status"]=0;
            $arr["error"]="数据异常！";
        }
        if($orderModel->create($data)){
            $businessList = array();
            $data["lcd"] = date("Y-m-d H:i:s");
            $order_id = $orderModel->add();//添加訂單
            $orderPrefix = $cityViewModel->where(array("id"=>$data["city_id"]))->getField("web_prefix");
            $order_code = strToCodeLength($order_id,$orderPrefix);//計算訂單編號
            $addStaData=array();//s_type s_code "order_id"=>$order_id,"status"=>"send"
            foreach ($data["business"] as  $business){ //根據害蟲名字查詢出害蟲id
                $map['name'] = $business;
                $map['city_id'] = $data["city_id"];
                $rs = $businessModel->where($map)->find();
                if($rs){
                    $s_code = $rs["type"] == 0?$order_code."B":$order_code."A";
                    $data["template"][$rs["type"]]["s_code"]=$s_code;//發消息的模板使用（用於分類害蟲）
                    $data["template"][$rs["type"]]["name"][]=$business;//發消息的模板使用（用於分類害蟲）
                    if(empty($addStaData)){
                        $data["order_type"] = intval($rs["type"]);
                        $addStaData[0]=array("order_id"=>$order_id,"status"=>"send","s_type"=>$rs["type"],"s_code"=>$s_code);
                    }elseif ($data["order_type"] !== intval($rs["type"]) && $data["order_type"] !== 2){
                        $data["order_type"] = 2;//混合訂單
                        $addStaData[1]=array("order_id"=>$order_id,"status"=>"send","s_type"=>$rs["type"],"s_code"=>$s_code);
                    }
                    $businessList[]=array("bus_type"=>$rs["type"],"order_id"=>$order_id,"bus_id"=>$rs["id"]);
                }
            }
            //var_dump($data["template"]);die();
            if(empty($businessList)){
                $arr["status"]=0;
                $arr["error"]="害虫不存在，数据异常！";
                $orderModel->where(array("id"=>$order_id))->delete();
            }else{
                $orderBusModel->addAll($businessList);//給訂單添加害蟲
                $updateDate = array("order_code"=>$order_code,"address"=>$data["address"],"order_type"=>$data["order_type"]);
                $orderModel-> where(array("id"=>$order_id))->setField($updateDate);//訂單編號、詳細地址修改
                foreach ($addStaData as $addData){ //添加A、B類訂單
                    $orderStaModel->create($addData);
                    $sta_id = $orderStaModel->add();
                    $data["sta_id"] = $sta_id;//最後跳轉的id
                    $data["template"][$addData["s_type"]]["sta_id"] =$sta_id;

                    $orderHisModel->create(array(
                        "sta_id"=>$sta_id,
                        "status"=>"new",
                    ));
                    $orderHisModel->add();//記錄操作

                    $data["openid"] = session("access_token")["openid"];
                    $data["order_sta_id"] = $sta_id;//最後跳轉的id
                    $orderWechatModel->create($data);
                    $orderWechatModel->add();//添加微信綁定
                }
            }
        }else{
            $arr["status"]=0;
            $arr["error"] = $orderModel->getError();
        }
        return $arr;
    }

    //獲取三維數組（城市關係表)
    public function getThreeCityList(){
        $regionModel = D("Region");
        $cityModel = D("City");
        $areaModel = D("Area");
        $regionList = $regionModel->order("z_index desc")->getField('id,region_name as name,region_name_tw');
        if($regionList){
            foreach ($regionList as &$region){
                $region["list"] = $cityModel->where(array("region_id"=>$region["id"]))->order("z_index desc")->getField('id,city_name as name,b_unit,other_open');
                if($region["list"]){
                    foreach ($region["list"] as &$city){
                        $city["b_unit"] = L($city["b_unit"]);
                        $city["list"] = $areaModel->where(array("city_id"=>$city["id"]))->order("z_index desc")->getField('id,area_name as name,area_name_tw');
                        if ($city["other_open"] == 1){
                            $city["list"][]=array("id"=>0,"name"=>"其它区域");
                        }
                    }
                }
            }
        }
        return $regionList;
    }

    //獲取害蟲模板（因為所有城市的害蟲一致，所以不做ajax查詢，如果有改動需要修改)
    public function getBusinessList(){
        $cityModel = D("City");
        $businessModel = D("Business");
        $city_id = $cityModel->field("id")->order("z_index desc")->getField("id");
        $arr = $businessModel->where(array("city_id"=>$city_id))->getField('name',true);
        return $arr;
    }

    //獲取訂單詳情（根據sta_id)
    public function getOrderListToStaId($sta_id){
        $arr["status"] = 1;
        $openid = session("access_token")["openid"];
        if(empty($openid)||empty($sta_id)){
            $arr["status"]=0;
            $arr["error"] = "订单异常，请刷新重试";
        }else{
            $orderWeChatViewModel = D("OrderWechatView");
            $orderList = $orderWeChatViewModel->where(array("order_sta_id"=>$sta_id,"openid"=>$openid))->find();
            if($orderList){
                $this->resetOrderOnlyOne($orderList);
                $orderList["door_in"] .= "（".L($orderList["b_unit"])."）"; //currency_type
                $statusList = array("send","ok_order","oo_order","new","update","order_back","modified");
                $orderList["payOrder"] = "close";
                if(in_array($orderList["status"],$statusList)&&empty($orderList["weChat_state"])&&$orderList["total_price"]!="等待管理员报价"){
                    $orderList["payOrder"] = "open";
                }
                $arr["order"] = $orderList;
            }else{
                $arr["status"]=0;
                $arr["error"] = "订单不存在或订单未绑定";
            }
        }

        return $arr;
    }
}