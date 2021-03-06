<?php
// 本类由系统自动生成，仅供测试用途
namespace Sws\Controller;
use Sws\Common\BaseController;
use Sws\Service\PHPExcelService;

class IndexController extends BaseController {
    public function index(){
        $user = session("user");
        $orderStaViewModel = D("OrderStaView");
        $cityModel = D("City");
        $date=new \DateTime();
        $date->modify('this week');
        $first_day_of_week=$date->format('Y-m-d 00:00:00');
        $date->modify('this week +6 days');
        $end_day_of_week=$date->format('Y-m-d 23:59:59');
        $cityMap["id"]= array('in',$user["city_auth"]);
        $cityList = $cityModel->where($cityMap)->order('region_id,z_index asc')->getField('id,city_name'.getNamePrefix());
        $map["lcd"]=array(array('EGT',$first_day_of_week),array('ELT',$end_day_of_week));
        $map["city_id"]= array('in',$user["city_auth"]);
        $count = $orderStaViewModel->where($map)->count();
        $map['status']='send';
        $count2 = $orderStaViewModel->where($map)->count();

        $this->assign("count",$count);
        $this->assign("count2",$count2);
        $this->assign("thisYear",date("Y"));
        $this->assign("cityList",$cityList);
        $this->assign("lang",strtolower(LANG_SET));
        $this->display();
	}

	public function ajaxTongJi(){
        if(IS_AJAX){
            $user = session("user");
            $orderStaViewModel = D("OrderStaView");
            $city = I("city_id",0);
            $arrMonth=array(L("January"), L("February"), L("March"), L("April"), L("May"), L("June"), L("July"), L("August"), L("September"), L("October"), L("November"), L("December"));
            $arrPC=array();
            $arrWeChat=array();
            $cityList = explode(",",$user["city_auth"]);

            foreach ($arrMonth as $key=>$value){
                $month = date("m");
                $starTime = date("Y")."-".($key+1)."-1 00:00:00";
                $endTime  = date("Y")."-".($key+1)."-31 23:59:59";
                //$sql = "select sum(case when from_order=0 then 1 else 0 end ) AS pc_sum,sum(case when from_order=1 then 1 else 0 end ) AS weChat_sum from __USER__ where lcd >='$starTime' AND lcd <='$endTime' ";
                $map["lcd"]=array(array('EGT',$starTime),array('ELT',$endTime));
                if (is_numeric($city)&&$city!=0&&in_array($city,$cityList)){
                    $map["city_id"] = $city;
                    //$sql.=" and city_id='$city'";
                }else{
                    //$user["city_auth"] = rtrim($user["city_auth"],",");
                    //$sql.=" and city_id in (".$user["city_auth"].")";
                    $map["city_id"]= array('in',$user["city_auth"]);
                }
                if($month < $key+1){
                    $count = array(
                        "weChat_sum"=>0,
                        "pc_sum"=>0,
                    );
                }else{
                    $count = $orderStaViewModel->field('sum(case when from_order=0 then 1 else 0 end ) AS pc_sum,sum(case when from_order=1 then 1 else 0 end ) AS weChat_sum')->where($map)->find();
                }
                $count["pc_sum"]=empty($count["pc_sum"])?0:$count["pc_sum"];
                $count["weChat_sum"]=empty($count["weChat_sum"])?0:$count["weChat_sum"];
                array_push($arrPC,$count["pc_sum"]);
                array_push($arrWeChat,$count["weChat_sum"]);
            }

            $this->success(array("arrMonth"=>$arrMonth,"arrPC"=>$arrPC,"arrWeChat"=>$arrWeChat));
        }else{
            $this->redirect("/sws/index/index");
        }
    }

    public function zhcn(){
        $url = $_GET["return"];
        cookie('think_language','zh-cn');
        if(!empty($url)){
            $url = strtr($url,"唷","/");
            $url = base64_decode($url);
            redirect($url);
        }else{
            $this->redirect("/sws/index/index");
        }
	}
    public function zhtw(){
        $url = $_GET["return"];
        cookie('think_language','zh-tw');
        if(!empty($url)){
            $url = strtr($url,"唷","/");
            $url = base64_decode($url);
            redirect($url);
        }else{
            $this->redirect("/sws/index/index");
        }
    }
    public function enus(){
        $url = $_GET["return"];
        cookie('think_language','en-us');
        if(!empty($url)){
            $url = strtr($url,"唷","/");
            $url = base64_decode($url);
            redirect($url);
        }else{
            $this->redirect("/sws/index/index");
        }
    }

    //導出excel
    public function downExcel(){
        if (IS_POST){
            $data = I("post.");
            if(empty($data["start_date"])||empty($data["end_date"])){
                $this->error("時間不能為空","/sws/index/index",5);
                return false;
            }
            if($data["start_date"] > $data["end_date"]){
                $this->error("開始時間不能大於結束時間","/sws/index/index",5);
                return false;
            }
            $phpExcelService = new PHPExcelService();
            $phpExcelService->downIndexExcel($data);
            $phpExcelService->outPrint();
        }else{
            $this->redirect("/sws/index/index");
        }
    }

    //修改無效數據
    public function test(){
        $orderStaModel = D("OrderSta");//訂單狀態、價格表
        $orderHisModel = D("OrderHis");//訂單記錄表
        $rows = $orderStaModel->where(array("status"=>"service"))->select();
        foreach ($rows as $row){
            $orderHisModel->where(array("sta_id"=>$row["id"],"status"=>"ok_order","lud"=>array("EGT",$row["lud"])))
                ->save(array("status"=>"finish"));
        }
        $orderStaModel->where(array("status"=>"service"))->save(array("status"=>"finish"));
    }

    //修改自動過期訂單的bug數據
    public function test2(){
        $orderStaModel = D("OrderSta");//訂單狀態、價格表
        $orderHisModel = D("OrderHis");//訂單記錄表
        $rows = $orderHisModel->field('sta_id,count(status) as s_sum')->where(array("status"=>"auto_rejected"))->group('sta_id')->select();
        if($rows){
            foreach ($rows as $row){
                if($row["s_sum"]>1){
                    $orderHisModel->where(array("status"=>array("in",array("overdue_email","auto_rejected")),"sta_id"=>$row["sta_id"]))->delete();
                    $hisRow = $orderHisModel->where(array("sta_id"=>$row["sta_id"]))->order('lcd desc')->find();
                    if($hisRow["status"]=="ok_order"){
                        $orderStaModel->where(array("status"=>"auto_rejected","id"=>$row["sta_id"]))->save(array("status"=>"finish"));
                    }
                }
            }
        }
    }
}