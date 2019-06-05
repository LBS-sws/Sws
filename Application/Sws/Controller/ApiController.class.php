<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;

use Sws\Common\ApiValidateController;
use Sws\Service\OrderWeChatService;
use Sws\Service\WeChatCallBackApiService;

class ApiController extends ApiValidateController {

    //設置公眾號的菜單
    public function createMenu(){
        $weChat = $this->_weChat;
        $weChat->createMenu();
        die();
	}

    //設置公眾號的行業
    public function setIndustry(){
        $weChat = $this->_weChat;
        $weChat->setIndustry();
        die();
	}

    //綁定訂單
    public function bindingOrder(){
        $weChat = $this->_weChat;
        L("loginTitle","LBS - 绑定订单");
        $this->assign("order",array());
        $this->assign("signPackage",json_encode($weChat->getSignPackage()));
        $this->display("WeChat:bindingOrder");
	}

    //綁定訂單(保存)
    public function saveBinding(){
        if(IS_POST){
            $data = I("post.");
            //$data["openid"] = session("access_token")["openid"];
            $OrderWeChatService = new OrderWeChatService();
            $arr = $OrderWeChatService->saveBinding($data);
            if($arr["state"] == 0){
                $weChat = $this->_weChat;
                $this->assign("hint_title",$arr["error"]);
                $this->assign("order",$data);
                $this->assign("signPackage",json_encode($weChat->getSignPackage()));
                $this->display("WeChat:bindingOrder");
            }else{
                $this->assign("hint_title","绑定成功");
                $this->redirect("sws/api/orderList");
            }
        }else{
            $this->redirect("sws/api/bindingOrder");
        }
	}

    //新訂單
    public function newOrder(){
        //已登錄
        L("loginTitle","LBS - 新订单");
        $weChat = $this->_weChat;
        $orderWeChatService = new OrderWeChatService();
        $cityList = $orderWeChatService->getThreeCityList();
        $businessList = $orderWeChatService->getBusinessList();
        //var_dump($cityList);
        $this->assign("cityList",json_encode($cityList));
        $this->assign("businessList",$businessList);
        $token = time().randomStrAZ();
        if(IS_POST){
            $data = I("post.");
            $arr = $orderWeChatService->saveNewOrder($data);
            if($arr["status"] == 0){//添加失敗
                $token = $data["token"];
                $this->assign("order",$data);
                $this->assign("hint_title",$arr["error"]);
            }else{ //下單成功，發送模板信息
                $weChat->sendTemplateToOrder($data);
                //$this->redirect("sws/api/closeWeChat");//關閉微信頁面
                $this->redirect("sws/api/editOrder",array("id"=>$data["order_sta_id"]));//跳轉到詳情頁面
            }
        }
        $this->assign("token",$token);
        $this->assign("signPackage",json_encode($weChat->getSignPackage(),true));
        $this->display("WeChat:newOrder");
	}

    //訂單列表
    public function orderList(){
        $type=I("type","");
        $page=I("page",1);
        $weChat = $this->_weChat;
        $OrderWeChatService = new OrderWeChatService();
        $orderList = $OrderWeChatService->getOrderList($type,$page);
        if(IS_AJAX){
            $this->ajaxReturn(array("status"=>1,"orderList"=>$orderList));
        }else{
            //var_dump($orderList);
            L("loginTitle","LBS - 订单列表");
            $this->assign("orderList",$orderList);
            $this->assign("signPackage",json_encode($weChat->getSignPackage()));
            $this->display("WeChat:orderList");
        }
    }

    //訂單詳情
    public function editOrder(){
        $sta_id = I("id","");
        $weChat = $this->_weChat;

        L("loginTitle","LBS - 订单详情");
        $OrderWeChatService = new OrderWeChatService();
        $orderList = $OrderWeChatService->getOrderListToStaId($sta_id);
        if($orderList["status"] == 1){
            $this->assign("order",$orderList["order"]);
        }else{
            $this->assign("hint_title",$orderList["error"]);
        }
        $this->assign("signPackage",json_encode($weChat->getSignPackage()));
        $this->display("WeChat:editOrder");
	}

    //訂單支付
    public function payOrder(){
        $sta_id = I("id","");
        header('Content-Type:text/html; charset=utf-8;');
        $this->redirect("sws/api/editOrder",array("id"=>$sta_id),3,"功能受限制，无法实现");
	}

    //订阅授权
    public function subscribeLbs(){
        $weChat = $this->_weChat;
        $url = $weChat->subscribeLbs();
        //var_dump($url);die();
        header("location:$url");
        exit;
	}

    //微信接口配置（關注、菜單按鈕事件）
    public function weChatBack(){
        $weChatObj = new WeChatCallBackApiService();
        if (!isset($_GET['echostr'])) {
            $weChatObj->responseMsg();
        }else{
            $weChatObj->valid();
        }
	}

    //微信定位當前位置（ajax）
    public function ajaxLocation(){
        if (IS_AJAX) {
            $weChat = $this->_weChat;
            $type=I("res","");
            $result = $weChat->getAddressToLocation($type);
            $result = $weChat->resetCity($result);
            if($result){
                $this->ajaxReturn(array("status"=>1,"list"=>$result));
            }else{
                $this->ajaxReturn(array("status"=>0,"list"=>""));
            }
        }else{
            $this->redirect("sws/api/orderList",array(),6,"666666666");
        }
	}

    public function test(){
        session("access_token",null);
        var_dump("test");
        die();
	}

    public function closeWeChat(){
        $weChat = $this->_weChat;
        L("loginTitle","");
        $this->assign("signPackage",json_encode($weChat->getSignPackage()));
        $this->display("WeChat:closeWeChat");
	}
}