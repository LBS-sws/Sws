<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 */
namespace Sws\Service;
vendor('PHPExcel.PHPExcel');
class PHPExcelService{
    protected $objPHPExcel;
    protected $sheetNum;
    protected $listArr=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    protected $row = 1;

    public function __construct() {
        $this->objPHPExcel = new \PHPExcel();//这里要注意‘\’ 要有这个。因为版本是3.1.2了。
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->sheetNum = 0;
        $this->objPHPExcel->getActiveSheet()->setTitle(L("order_count"));//设置sheet的name

        $this->objPHPExcel->getProperties()->setCreator(L("email_sws"));//创建人
        $this->objPHPExcel->getProperties()->setLastModifiedBy(L("email_sws"));//最后修改人
        $this->objPHPExcel->getProperties()->setTitle(L("downTitle"));//标题
        $this->objPHPExcel->getProperties()->setSubject(L("loginTitle")."00");//题目
        $this->objPHPExcel->getProperties()->setDescription(L("email_28"));//描述
        $this->objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");//关键字
        $this->objPHPExcel->getProperties()->setCategory("Test result file");//种类
    }

    //首頁導出excel
    public function downIndexExcel($data){
        $user = session("user");
        $prefix = getNamePrefix();
        $define = C('DEFINE');
        $orderBusViewModel = D("OrderBusView");
        $orderStaViewModel = D("OrderStaView");
        if(empty($data["city_id"])){
            $map["city_id"]= array('in',$user["city_auth"]);
        }else{
            $map["city_id"]= $data["city_id"];
        }
        $map["lcd"]=array(array('EGT',$data["start_date"]." 00:00:00"),array('ELT',$data["end_date"]." 23:59:59"));
        $orderList = $orderStaViewModel->where($map)->order("city_id desc")->select();
        foreach ($orderList as $key => $order){
            $businessList =$orderBusViewModel->where(array("order_id"=>$order["order_id"],"type"=>$order["s_type"]))->select();
            $order["business_list"] = $businessList;
            setOrderTotalPrice($order);
            $business_name = array();
            $order["appellation_ns"] = L($define["appellation_list"][$order["appellation"]]);
            $order["b_unit_ns"] = L($order["b_unit"]);
            if($order["s_type"]== 0 && $order["status"]=="send"){
                $order["status"] = L("order_status_sales");
            }elseif ($order["s_type"]==1 && $order["status"]=="send"){
                $order["status"] = L("send_new");
            }else{
                $order["status"] = L($order["status"]);
            }
            foreach ($businessList as $business){
                array_push($business_name,$business["name".$prefix]);
            }
            $order["business_name"] = empty($business_name)?"":implode("、",$business_name);
            if($key!=0){
                if($order["city_id"]!=$orderList[$key-1]["city_id"]){
                    $this->addNewSheet($order["city_name".$prefix]);
                    $this->fillIndexExcelTitle();
                }
            }else{
                $this->setThisTitle($order["city_name".$prefix]);
                $this->fillIndexExcelTitle();
            }
            $this->fillIndexExcelBody($order);
        }
    }

    public function fillIndexExcelBody($order){
        $prefix = getNamePrefix();
        $arr=array('s_code','order_name','appellation_ns','email','phone','region_name'.$prefix,"city_name".$prefix,"area_name".$prefix,
            "address","business_name","door_in","lcd","service_time","lud","status","total_price");
        foreach ($arr as $key =>$value){
            $column = $this->listArr[$key];
            $row = $column.($this->row);
            if($value==="door_in"){
                $fill = $order[$value].$order["b_unit_ns"];
            }elseif($value==="total_price"){
                $fill = $order[$value]."(".$order["currency_type"].")";
            }else{
                $fill = $order[$value];
            }
            $this->objPHPExcel->getActiveSheet()->setCellValue($row, $fill);
        }
        $this->row++;
    }

    public function fillIndexExcelTitle(){
        $arr=array(
            array(L("order_code"),12),
            array(L("down_name"),12),
            array(L("down_appellation"),12),
            array(L("down_email"),20),
/*            array(L("client").L("order_name"),12),
            array(L("client").L("appellation"),12),
            array(L("client").L("order_email_only"),20),*/
            array(L("phone"),15),
            array(L("region_name"),12),
            array(L("city_name"),12),
            array(L("area_name"),12),
            array(L("address"),40),//詳細地址
            array(L("Infestation"),40),//害蟲問題
            array(L("down_area"),12),//治理范围
            array(L("order_time"),20),//下單時間
            array(L("service_time"),20),//服务时间
            array(L("end_update_time"),30),//最後一次的修改時間
            array(L("order_status"),12),//订单状态
            array(L("total_price"),30),//總價
        );
        foreach ($arr as $key =>$value){
            $column = $this->listArr[$key];
            $row = $column.($this->row);
            if(empty($value[1])){
                $this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
            }else{
                $this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($value[1]);
            }
            $this->objPHPExcel->getActiveSheet()->setCellValue($row, $value[0]);
        }
        $this->row++;
    }

    public function setThisTitle($str){
        $this->objPHPExcel->getActiveSheet()->setTitle($str);//设置sheet的name
    }

    //添加新的sheet
    public function addNewSheet($sheetName=""){
        $this->sheetNum++;
        $this->objPHPExcel->createSheet();
        $this->objPHPExcel->setActiveSheetIndex($this->sheetNum);
        $this->row = 1;
        if(!empty($sheetName)){
            $this->objPHPExcel->getActiveSheet()->setTitle($sheetName);
        }
    }
    public function outPrint($str){
        $str = empty($str)?L("loginTitle"):$str;
        //在浏览器输出表格
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header("Content-Disposition:attachment;filename=$str.xls");
        header("Content-Transfer-Encoding:binary");

        $objWriter = new \PHPExcel_Writer_Excel5($this->objPHPExcel);//设置保存版本设置当前的sheet
        $objWriter->save('php://output');
    }
}