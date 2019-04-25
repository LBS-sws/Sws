<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 * 主要用於管理員操作訂單
 */
namespace Sws\Service;
class KehuService{
    protected $_key = "13e3b26354aaf4b85978b23826516c30";
    protected $_types = array('120000');

    protected $_orderList;
    protected $_city;

    //關鍵字查詢
    public function mapSearch($keywords,$page=1){
        $page = intval($page);
        $city = $this->_city;
        $key = $this->_key;
        $types = implode("|",$this->_types);
        $url = "http://restapi.amap.com/v3/place/text?output=JSON&citylimit=true&city=$city&keywords=$keywords&key=$key&types=$types&page=$page";
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        //var_dump($json_data);
        $html = "";
        if ($json_data->status == 1){
            $rows = $json_data->pois;
            if(!empty($rows)){
                $key = ($page-1)*20;
                foreach ($rows as $row){
                    $key++;
                    $html.='<li><div class="media"><div class="media-left media-middle">';
                    $html.='<span class="badge">'.$key.'</span></div><div class="media-body media-middle">';
                    if(is_array($row->address)){
                        $html.='<span>'.$row->name.'</span>';
                    }else{
                        $html.='<span>'.$row->name.'（'.$row->address.'）</span>';
                    }
                    $html.='</div><div class="media-right media-middle">';
                    $html.='<button type="button" class="btn btn-kehu pull-right okSelect">'.L("address_01_10").'</button>';
                    $html.='</div></div></li>';
                }
            }
        }

        return $html;
    }

    //搜索地區
    public function mapSelect($keywords="",$page=1){
        $page = intval($page);
        $key = $this->_key;
        $html = "";
        if (empty($keywords)){
            $keywords = $this->_city;
        }
        if (!strpos($keywords,",")){
            $url = "http://restapi.amap.com/v3/config/district?keywords=$keywords&subdistrict=2&key=$key&page=$page";
            //var_dump($url);
            $address_data = file_get_contents($url);
            $json_data = json_decode($address_data);
            if ($json_data->status == 1){
                $rows = $json_data->districts;
                if(!empty($rows)){
                    $rows = $rows[0];
                    $rows = $rows->districts;
                    $key = ($page-1)*20;
                    foreach ($rows as $row){
                        $key++;
                        $html.='<li><div class="media"><div class="media-left media-middle">';
                        $html.='<span class="badge">'.$key.'</span></div><div class="media-body media-middle">';
                        $html.='<span>'.$row->name.'</span>';
                        $html.='</div><div class="media-right media-middle">';
                        if(empty($row->districts)||count($row->districts)<2){
                            $html.='<button type="button" class="btn btn-kehu pull-right ajaxSelect" data-value="'.$row->center.'">'.L("address_01_10").'</button>';
                        }else{
                            $html.='<button type="button" class="btn btn-kehu pull-right ajaxSelect" data-value="'.$row->adcode.'">'.L("address_01_10").'</button>';
                        }
                        $html.='</div></div></li>';
                    }
                }else{
                    //需要搜索周邊
                    $html = $this->mapRound($keywords,1000,$page);
                }
            }
        }else{
            //需要搜索周邊
            $html = $this->mapRound($keywords,1000,$page);
        }

        return $html;
    }

    //搜索周邊
    public function mapRound($location="",$radius = 3000,$page=1){
        $page = intval($page);
        $key = $this->_key;
        //$location = "116.456299,39.960767";
        $types = implode("|",$this->_types);
        $url = "http://restapi.amap.com/v3/place/around?key=$key&location=$location&output=JSON&radius=$radius&types=$types&page=$page";
        $url.="&city=".$this->_city;
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        $html = "";
        if ($json_data->status == 1){
            $rows = $json_data->pois;
            if(!empty($rows)){
                $key = ($page-1)*20;
                foreach ($rows as $row){
                    $key++;
                    $html.='<li><div class="media"><div class="media-left media-middle">';
                    $html.='<span class="badge">'.$key.'</span></div><div class="media-body media-middle">';
                    if(is_array($row->address)){
                        $html.='<span>'.$row->name.'</span>';
                    }else{
                        $html.='<span>'.$row->name.'（'.$row->address.'）</span>';
                    }
                    $html.='</div><div class="media-right media-middle">';
                    $html.='<button type="button" class="btn btn-kehu pull-right okSelect">'.L("address_01_10").'</button>';
                    $html.='</div></div></li>';
                }
            }
        }

        return $html;
    }

    //定位當前位置
    public function ipNow($page=1){
        $ip = get_client_ip();
        $page = intval($page);
        $key = $this->_key;
        $url = "http://restapi.amap.com/v3/ip?output=JSON&key=$key&ip=$ip";

        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        $html = "";
        if($json_data->status == 1){
            $rectangle = explode(";",$json_data->rectangle);
            $rectangle = $rectangle[0];
            $rectangle = explode(",",$rectangle);
            $rectangle = sprintf("%.6f",$rectangle[0]).",".sprintf("%.6f",$rectangle[0]);
            $html = $this->mapRound($rectangle,100,$page);

        }

        return $html;
    }

    public function validate($sta_id,$token){
        $orderStaViewModel = D("OrderStaView");
        $map["id"] = $sta_id;
        $map["s_type"] = 1;//一般業務
        $map["kehu_set"] = 0;
        $map["token"] = $token;
        $map["status"]=array("neq","finish");
        $end_day = date("Y-m-d H:i:s");
        $first_day = date('Y-m-d H:i:s', strtotime("$end_day -7 day"));
        $map["lcd"]=array(array('EGT',$first_day),array('ELT',$end_day));
        $rs = $orderStaViewModel->where($map)->find();
        if($rs){
            $this->_orderList = $rs;
            if($this->_orderList["region_name"]!="中国"){
                $this->_city = $this->_orderList["region_name"];
            }else{
                $this->_city = $this->_orderList["city_name"];
            }
            return true;
        }else{
            return false;
        }
    }

    public function saveKehu($data,$his_data,$email_title,$rs,$type = ""){
        $orderStaModel = D("OrderSta");//訂單狀態、價格表
        $orderHisModel = D("OrderHis");//訂單記錄表
        $orderBusViewModel = D("OrderBusView");

        $orderStaModel->create($data);
        $orderStaModel->save();//修改訂單狀態
        $orderHisModel->create($his_data);
        $orderHisModel->add();//記錄操作
        //管理員主旨修改（開始)
        $businessList = $orderBusViewModel->where(array("order_id"=>$rs["order_id"],"type"=>$rs["s_type"]))->select();
        $define = C('DEFINE');
        $prefix = getNamePrefix($rs["kehu_lang"]);
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
        $email_admin->setAdminToEmail($rs["city_id"],$rs["id"],$type);
        $email_admin->sendMail();//給管理員發郵件
    }

    public function getOrderList(){
        return $this->_orderList;
    }
}