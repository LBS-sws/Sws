<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 */
namespace Sws\Service;
use Sws\Logic\PdfLogic;

class PdfService{
    protected $_PDF;
    private $_lcd;

    //210mm×297mm
    public function __construct() {
        $this->_PDF = new PdfLogic();
        // 是否显示页眉
        $this->_PDF->setPrintHeader(true);
        // 是否显示页脚
        $this->_PDF->setPrintFooter(true);
        // 设置是否自动分页  距离底部多少距离时分页
        $this->_PDF->SetAutoPageBreak(TRUE, '27');
        // 设置默认等宽字体
        $this->_PDF->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // 设置行高
        $this->_PDF->setCellHeightRatio(1);
        // 设置左、上、右的间距
        $this->_PDF->SetMargins('10', '50', '10');
        // 设置图像比例因子
        $this->_PDF->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->_PDF->setFontSubsetting(true);
    }

    //第二頁內容
    private function setTwoPage($order,$kehu_lang){
        $prefix = getNamePrefix($kehu_lang);
        $this->_PDF->AddPage();
/*        $this->_PDF->SetFillColorArray(array(231,56,40));
        $this->_PDF->writeHTMLCell(210, 5, 0,15, " ", 0, 1, true, true, '', true);
        $this->_PDF->writeHTMLCell(210, 5, 0,275, " ", 0, 1, true, true, '', true);
        $this->_PDF->SetFillColorArray(array(236,104,42));
        $this->_PDF->writeHTMLCell(210, 5, 0,20, " ", 0, 1, true, true, '', true);
        $this->_PDF->writeHTMLCell(210, 5, 0,280, " ", 0, 1, true, true, '', true);
        $this->_PDF->SetFillColorArray(array(255,255,255));
        $this->_PDF->writeHTMLCell(32, 40, 9,5, " ", 0, 1, true, true, '', true);
        $this->_PDF->writeHTMLCell(56, 18, 147,271, " ", 0, 1, true, true, '', true);
        $this->_PDF->Image('Public/sws/img/'.L("img_1_1"),10,5,30,40);
        $this->_PDF->Image('Public/sws/img/'.L("img_1_2"),45,10,72,10);
        $this->_PDF->Image('Public/sws/img/'.L("img_2"),150,271,50,18);*/

        $lcd = strtotime($order["lcd"]);
        $this->_lcd = $lcd;
        $lrd = strtotime($order["lcd"]." +7 day");
        // 合同編號
        $html = "<p><b>".L("pdf_01")."</b>：".$order["order_code"]."</p>";
        $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
        if($prefix == "_us"){
            $this->_PDF->writeHTMLCell(95, 5, 10,55, $html, 0, 1, 0, true, '', true);
        }else{
            $this->_PDF->writeHTMLCell(63, 5, 10,55, $html, 0, 1, 0, true, '', true);
        }
        
        if($order["status"] == 'send'||$order["status"] == 'modified'){
            //報價日期
            $html = "<p><b>".L("pdf_03")."</b>：".date("Y-m-d",$lcd)."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
            if($prefix == "_us"){
                $this->_PDF->writeHTMLCell(95, 5, 105,55, $html, 0, 1, 0, true, 'R', true);
            }else{
                $this->_PDF->writeHTMLCell(63, 5, 73,55, $html, 0, 1, 0, true, '', true);
            }

            //合約有效期
            $html = "<p><b>".L("pdf_02")."</b>：".date("Y-m-d",$lrd)."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
            if($prefix == "_us"){
                $this->_PDF->writeHTMLCell(95, 5, 105,62, $html, 0, 1, 0, true,'R', true);
            }else{
                $this->_PDF->writeHTMLCell(64, 5, 136,55, $html, 0, 1, 0, true,'R', true);
            }
        }else{
            //確定日期
            $lcd = strtotime($order["determine"]);
            $html = "<p><b>".L("pdf_20")."</b>：".date("Y-m-d",$lcd)."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
            if($prefix == "_us"){
                $this->_PDF->writeHTMLCell(100, 5, 105,55, $html, 0, 1, 0, true, '', true);
            }else{
                $this->_PDF->writeHTMLCell(63, 5, 73,55, $html, 0, 1, 0, true, '', true);
            }
        }

        //客戶资料
        $html = "<p><b>".L("pdf_04")."：</b></p>";
        $this->_PDF->SetFont('stsongstdlight', '', 16, '', true);
        $this->_PDF->writeHTMLCell(60, 5, 10,68, $html, 0, 1, 0, true, '', true);

        //繪製錶格
        $this->_PDF->SetLineStyle(array('width' => 0.2, 'cap' => 'square', 'join' => 'miter', 'dash' => '0', 'color' => array(0, 0,0)));
        //$order["address"]="11111";
        $changeHeight = 0;
        if(!empty($order["address"])){
            $changeHeight = -10;
        }
        //横线
        $this->_PDF->Line(10,75,200,75);
        $this->_PDF->Line(10,145,200,145);
        //竖线
        $this->_PDF->Line(10,75,10,145);
        $this->_PDF->Line(200,75,200,145);

        //客戶姓名
        if($prefix == "_us"){
            $html = "<p><b>".L("pdf_05")."：</b>".$order["appellation_ns"]."  ".$order["order_name"]."</p>";
        }else{
            $html = "<p><b>".L("pdf_05")."：</b>".$order["order_name"]."  ".$order["appellation_ns"]."</p>";
        }
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $this->_PDF->writeHTMLCell(180, 5, 15,93+$changeHeight, $html, 0, 1, 0, true, '', true);
        //電郵
        $html = "<p><b>".L("pdf_06")."：</b>".$order["email"]."</p>";
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $this->_PDF->writeHTMLCell(120, 5, 15,103+$changeHeight, $html, 0, 1, 0, true, '', true);
        //手機號碼
        $html = "<p><b>".L("order_phone")."：</b>".$order["phone"]."</p>";
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $this->_PDF->writeHTMLCell(120, 5, 90,103+$changeHeight, $html, 0, 1, 0, true, '', true);

        $regionModel = D("Region");
        $map["www_fix"] = $order["www_fix"];
        $row = $regionModel->where($map)->count();

        if($row>1){
            //地區
            $html = "<p><b>".L("region")."：</b>".$order["region_name$prefix"]."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
            $this->_PDF->writeHTMLCell(60, 5, 15,113+$changeHeight, $html, 0, 1, 0, true, '', true);
            //城市
            $html = "<p><b>".L("city")."：</b>".$order["city_name$prefix"]."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
            $this->_PDF->writeHTMLCell(60, 5, 90,113+$changeHeight, $html, 0, 1, 0, true, '', true);
            //區域
            $html = "<p><b>".L("area")."：</b>".$order["area_name$prefix"]."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
            $this->_PDF->writeHTMLCell(60, 5, 140,113+$changeHeight, $html, 0, 1, 0, true, '', true);
        }else{
            //城市
            $html = "<p><b>".L("city")."：</b>".$order["city_name$prefix"]."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
            $this->_PDF->writeHTMLCell(60, 5, 15,113+$changeHeight, $html, 0, 1, 0, true, '', true);
            //區域
            $html = "<p><b>".L("area")."：</b>".$order["area_name$prefix"]."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
            $this->_PDF->writeHTMLCell(60, 5, 90,113+$changeHeight, $html, 0, 1, 0, true, '', true);
        }
        //面積單位
        $b_unit = L($order["b_unit"]);
        $b_unit = $b_unit=="m²"?"m<sup><small>2</small></sup>":$b_unit;
        if(strtolower($order["web_prefix"])=='cn'){
            $html = "<p><b>".L("Indoor_area")."</b>：".$order["door_in"]." ".$b_unit."</p>";
        }else{
            $html = "<p><b>".L("Indoor_area_a")."</b>：".$order["door_in"]." ".$b_unit."</p>";
        }
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $this->_PDF->writeHTMLCell(120, 5, 15,123+$changeHeight, $html, 0, 1, 0, true, '', true);
        //客戶的害蟲列表
        $html = "<p><b>".L("Infestation")."</b>：";

        if(!empty($order["business_list"])){
            foreach($order["business_list"] as $business){
                if($business["type"] == 1){
                    $html.=$business["name".$prefix]."、";
                }
            }
        }
        $html.="</p>";
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $lineHeight = $this->_PDF->getCellHeightRatio();
        $this->_PDF->setCellHeightRatio(1.5);
        $this->_PDF->writeHTMLCell(100, 5, 90,123+$changeHeight, $html, 0, 1, 0, true, '', true);
        $this->_PDF->setCellHeightRatio($lineHeight);

        if(!empty($order["address"])){
            //客戶地址
            $html = "<p><b>".L("address")."：</b>".$order["address"]."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
            $this->_PDF->writeHTMLCell(170, 5, 15,123, $html, 0, 1, 0, true, '', true);
            //服務時間
            $html = "<p><b>".L("service_time")."：</b>".$order["service_time"].L("to").$order["service_time_end"]."</p>";
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
            $this->_PDF->writeHTMLCell(170, 5, 15,133, $html, 0, 1, 0, true, '', true);
        }

        //服務總價
        //$order["total_price"] = "123123.00";
        $price_text = $order["currency_type"].$order["total_price"];
        $html = "<p><b>".L("pdf_14")."：</b></p>";
        $this->_PDF->SetFont('stsongstdlight', '', 16, '', true);
        $this->_PDF->writeHTMLCell(45, 1, 10,143, "", 0, 1, 0, true, '', true);
        $this->_PDF->Write("",L("pdf_14")."：");
        $num_con = $this->_PDF->GetAbsX();
        $this->_PDF->Write("",$price_text);
        $price_len = $this->_PDF->GetAbsX()-$num_con+2;
        $this->_PDF->Line($num_con,154,$num_con+$price_len,154);
        $this->_PDF->Line($num_con,155,$num_con+$price_len,155);
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $this->_PDF->Write(6,"    ".L("pdf_21"));
/*        $this->_PDF->writeHTMLCell(45, 5, 10,150, $html, 0, 1, 0, true, '', true);
        $this->_PDF->SetFont('stsongstdlight', 'U', 16, '', true);
        $this->_PDF->writeHTMLCell(80, 5, 43,150, $price_text, 0, 1, 0, true, '', true);
        $this->_PDF->SetTextColorArray(array(255,255,255));
        $this->_PDF->Write("",$price_text);
        $this->_PDF->SetTextColorArray(array(0,0,0));
        $price_len = $this->_PDF->GetX()-10;
        $this->_PDF->Line(44,155,44+$price_len,155);
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $this->_PDF->writeHTMLCell(110, 5, 44+$price_len,151, L("pdf_21"), 0, 1, 0, true, '', true);*/

        //条款细则
        $html = "<p>".L("pdf_15")."</p>";
        $this->_PDF->SetFont('stsongstdlight', 'U', 11, '', true);
        $this->_PDF->writeHTMLCell(180, 5, 10,160, $html, 0, 1, 0, true, '', true);
        $this->_PDF->SetFont('stsongstdlight', '', 11, '', true);
/*        $html = "<p>".L("pdf_16")."</p>";
        $this->_PDF->SetFont('stsongstdlight', '', 11, '', true);
        $this->_PDF->setCellHeightRatio(1.5);
        $this->_PDF->writeHTMLCell(180, 5, 10,165, $html, 0, 1, 0, true, '', true);*/

        $html = htmlspecialchars_decode($order["terms$prefix"]);
        $this->_PDF->writeHTMLCell(190, "", 10,169, $html, 0, 1, 0, true, 'L', true);
        $this->_PDF->writeHTMLCell(190, 40, 10,"", "", 0, 1, 0, true, '', true);
        //$numPage = $this->_PDF->getPage();
        //var_dump($numPage);die();

        //蓋章
        $html = "<p>".$order["company".$prefix]."</p>";
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $this->_PDF->writeHTMLCell(100, 5, 105,226, $html, 0, 1, 0, true, 'C', true);
        $this->_PDF->Line(125,266,185,266);
        $this->_PDF->Image($order["seal"],139,233,30,0);

    }
//F
    public function outOrderPDF($order=array(),$file ="F",$kehu_lang=""){
        $this->setTwoPage($order,$kehu_lang);
        $path = "Public/sws/pdf";
        if (!file_exists($path)){
            mkdir ($path);
        }
        ob_clean();
        $this->_PDF->Output($path.'/'.$order["order_code"].'.pdf',$file);
    }

    private function getNumber($num){
        $arr=array( //治理類型
            "1"=>"One Prevention Application - No Guarantee",
            "2"=>"Two Prevention Applications – No Guarantee",
            "3"=>"Short Term Pest Protection Service – 1 month GUARANTEE <br>(3 applications in every 2 weeks intervals)",
            "5"=>"One Year Pest Protection Service - GUARANTEE & COST EFFECTIVE 5 apps <br>(2 initial applications within 14-21 days, 3 periodical applications in quarterly intervals)"
        );
        if (empty($arr[$num])){
            return "";
        }
        return $arr[$num];
    }
}