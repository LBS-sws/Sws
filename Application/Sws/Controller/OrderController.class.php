<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;
use Sws\Service\OrderService;
use Sws\Service\PdfService;

class OrderController extends BaseController {

    public function index(){
        $orderService = new OrderService();
        $orderList = $orderService->getOrderAllListToCityAJAX();
        $this->assign("orderList",$orderList);
        $this->assign("prefix",getNamePrefix());
        $this->display("./Order/index_ajax");
	}

    public function edit($index){
        $prefix = getNamePrefix();
        $orderService = new OrderService();
        $orderList = $orderService->getOrderListToId($index);
        if($orderList){
            $cityModel = D("City");
            $regionModel = D("Region");
            $cityList = $cityModel->order('region_id,z_index asc')->getField('id,currency_type,region_id,city_name'.$prefix);
            $regionList = $regionModel->order('z_index asc')->getField('id,web_prefix,region_name'.$prefix);
            //$orderList['region']=$cityList[$orderList['city_id']]['region_id'];
            $orderList["service_time"] =empty($orderList["service_time"])?"":date("Y-m-d H:i",strtotime($orderList["service_time"]));
            $orderList["service_time_end"] =empty($orderList["service_time_end"])?"":date("Y-m-d H:i",strtotime($orderList["service_time_end"]));
            $this->assign("prefix",$prefix);
            $this->assign("cityList",$cityList);
            $this->assign("regionList",$regionList);
            $this->assign("indoorList",getIndoorList());
            $this->assign("outdoorList",getOutdoorList());
            $this->assign("total_price",$orderList["total_price"]);

            $this->assign("lang",strtolower(LANG_SET));
            $this->assign("orderList",$orderList);
            $this->display("form");
        }else{
            $this->error("404:".L("operation_error"),"/sws/order/index",5);
        }
	}

    public function detail($index){
        $orderHisViewModel = D("OrderHisView");
        $orderService = new OrderService();
        $orderList = $orderService->getOrderListToId($index);
        $historyList = $orderHisViewModel->where(array("sta_id"=>$index))->order('lcd desc')->select();
        if($orderList){
            $orderList["from_order"] = empty($orderList["from_order"])?L("PC"):L("weChat");
            $orderList["service_time"] =empty($orderList["service_time"])?"":date("Y-m-d H:i",strtotime($orderList["service_time"]));
            $orderList["service_time_end"] =empty($orderList["service_time_end"])?"":date("Y-m-d H:i",strtotime($orderList["service_time_end"]));
            $this->assign("orderList",$orderList);
            $this->assign("historyList",$historyList);
            $this->assign("prefix",getNamePrefix());
            $this->assign("lang",strtolower(LANG_SET));
            $this->display("detail");
        }else{
            $this->error("404:".L("operation_error"),"/sws/order/index",5);
        }
	}

    public function save(){
        if (IS_POST){
            $data = I("post.");
            $user = session("user");
            $orderModel = D("Order");
            $orderStaViewModel = D("OrderStaView");
            $map["id"] = $data["id"];
            $map["city_id"]= array('in',$user["city_auth"]);
            $rs = $orderStaViewModel->where($map)->find();
            if(!$rs){ //驗證有訂單的權限且存在訂單
                $this->error(L("operation_error"),"/sws/order/index",5);
                return false;
            }
            $data["id"] = $rs["order_id"];
            if($orderModel->create($data,2)){
                $oldLang = LANG_SET;
                //害蟲驗證
                if(!validateBusinessList($data["business_id"],$rs["s_type"])){
                    $this->error("害蟲不存在","/sws/order/index",5);
                    return false;
                }
                $orderModel->save();
                $orderService = new OrderService();
                $orderService->updateOrderBusList($data,$rs);//訂單修改
                if(!empty($rs["s_type"])){ //非特殊業務則生成pdf文件
                    $pdfBool = true;
                    $pdf = new PdfService();
                    $pdf->outOrderPDF($orderService->order_list,'F',$rs["kehu_lang"]);
                    $html = $orderService->getOrderHtmlToKeHu($rs["kehu_lang"]);
                    sendMail($data['email'],L('email_45'),$html,$rs["s_code"],$pdfBool,L('email_28'));
                }

                // 切換语言包
                if(!empty($oldLang)){
                    $file   =   MODULE_PATH.'Lang/'.$oldLang.'.php';
                    if(is_file($file))
                        L(include $file);
                }
                session('TOP_TITLE',L('update_order_ok'));
                $this->redirect("/sws/order/edit",array("index"=>$rs["id"]));
            }else{
                $this->error($orderModel->getError(),"/sws/order/index",5);
            }
        }else{
            $this->error(L("illegal_request"),"/sws/order/index",5);
        }
	}

	//接受訂單
	public function service(){
        if (IS_POST){
            $orderService = new OrderService();
            $id = I("id");
            $orderList = $orderService->changeStatus($id,"service");
            if($orderList){
                session('TOP_TITLE',L('service_order_ok'));
                $this->redirect("/sws/order/detail",array("index"=>$id));
            }else{
                $this->error(L("operation_error"),"/sws/order/index",5);
            }
        }else{
            $this->error(L("illegal_request"),"/sws/order/index",5);
        }
    }

    //完成訂單
	public function finish(){
        if (IS_POST){
            $orderService = new OrderService();
            $id = I("id");
            $orderList = $orderService->changeStatus($id,"finish");
            if($orderList){
                session('TOP_TITLE',L('finish_order_ok'));
                $this->redirect("/sws/order/detail",array("index"=>$id));
            }else{
                $this->error(L("operation_error"),"/sws/order/index",5);
            }
        }else{
            $this->error(L("illegal_request"),"/sws/order/index",5);
        }
    }

    //拒絕訂單
	public function reject(){
        if (IS_POST){
            $orderService = new OrderService();
            $id = I("id");
            $orderList = $orderService->changeStatus($id,"reject");
            if($orderList){
                session('TOP_TITLE',L('service_order_no'));
                $this->redirect("/sws/order/detail",array("index"=>$id));
            }else{
                $this->error(L("operation_error"),"/sws/order/index",5);
            }
        }else{
            $this->error(L("illegal_request"),"/sws/order/index",5);
        }
    }

    //退回订单
	public function back($index=0){
        $orderService = new OrderService();
        $id = $index;
        $orderList = $orderService->backOrder($id);
        if($orderList){
            session('TOP_TITLE',L('back_ok'));
            $this->redirect("/sws/order/detail",array("index"=>$id));
        }else{
            $this->error(L("operation_error"),"/sws/order/index",5);
        }
    }

    //退回订单
	public function ajaxTotalPrice(){
        if (IS_AJAX) {
            $data = I("post.");
            $orderStaViewModel = D("OrderStaView");
            $businessModel = D("Business");
            $orderList = $orderStaViewModel->where(array("id"=>$data["id"]))->find();
            if($orderList){
                $orderList["total_price"]=0;
                $businessList = $businessModel->where(array("id"=>array('in',$data['business_id'])))->select();
                $orderList["business_list"] = $businessList;
                setOrderTotalPrice($orderList);//計算總價
                $this->ajaxReturn(array("status"=>1,"price"=>$orderList["total_price"]));
            }else{
                $this->ajaxReturn(array("status"=>0,"price"=>""));
            }
        }else{
            $this->redirect("sws/api/orderList",array(),6,"666666666");
        }
    }

    //訂單列表（異步請求列表）
	public function ajaxLoad(){
        if (IS_AJAX) {
            $data = I("get.");
            $orderService = new OrderService();
            $arr = $orderService->getOrderAllListToCityAJAX($data);
            $ajaxData = array(
                'draw'=>$data['draw'],
                'recordsTotal'=>$arr["total"],
                'recordsFiltered'=>$arr["filtered"],
                'data'=>$arr["orderList"],
            );
            $this->ajaxReturn($ajaxData);
            //var_dump($data);die();
        }else{
            $this->redirect("sws/api/orderList",array(),6,"666666666");
        }
    }
}