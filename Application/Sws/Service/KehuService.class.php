<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 * 主要用於管理員操作訂單
 */
namespace Sws\Service;
use Think\Model;

class KehuService{
    protected $_key = "13e3b26354aaf4b85978b23826516c30";
    protected $_types = array('120000');
    protected $_googleKey = "AIzaSyBu4AAWCQA7F-EKEkZWDKbpPw7w9OkSiy4";
    protected $_googleType = array('store');
/*    protected $_googleType = array('campground','hospital','local_government_office','lodging','meal_takeaway','night_club','park',
        'pharmacy','restaurant','shopping_mall','store','supermarket','synagogue'
    );*/

    protected $_orderList;
    protected $_city;
    protected $region;

    public $nextToKen = "";

    protected $validateArr=array();
    protected $addArr=array();
    protected $_NUM = 0;

    //關鍵字查詢
    public function mapSearch($keywords,$page=1){
        $page = intval($page);
        $city = $this->_city;
        $key = $this->_key;
        $types = implode("|",$this->_types);
        $url = "http://restapi.amap.com/v3/place/text?output=JSON&citylimit=true&city=$city&keywords=$keywords&key=$key&types=$types&page=$page&lang=en";
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        //var_dump($json_data);
        $html = "";
        if ($json_data->status == 1){
            $rows = $json_data->pois;
            if(!empty($rows)){
                $key = ($page-1)*20;
                foreach ($rows as $row){
                    $html.='<li>';
                    if(is_array($row->address)||strpos($row->name,'(')!==false){
                        $html.='<span>'.$row->name.'</span>';
                    }else{
                        $html.='<span>'.$row->name.'（'.$row->address.'）</span>';
                    }
                    $html.='</li>';
                }
            }
        }

        return $html;
    }

    //關鍵字查詢
    public function mapGoogleSearch($keywords,$nextToken=""){
        $keywords = trim($keywords);
        $region = strtolower($this->region);
        //$this->_city = "香港";
        if ($this->_city=="香港"){
            return $this->textSearchToHK($keywords);
        }
        $lang = $this->getLanguageToStr($keywords);
        $url="https://maps.googleapis.com/maps/api/place/autocomplete/json?";
        //$url="https://maps.googleapis.com/maps/api/place/queryautocomplete/json?";
        $urlArr = array(
            "input"=>$keywords,
            "language"=>$lang,
            "components"=>"country:$region",
            "key"=>$this->_googleKey
        );
        //if($region=='tw') $urlArr['location']='';
        $url.=http_build_query($urlArr);
        $address_data = $this->CurlRequest($url);
        $json_data = json_decode($address_data,true);
        //var_dump($json_data);
        $html = "";
        $this->nextToKen = "";
        if ($json_data['status'] == "OK"){
            $ZhService = new ZhConvertService();
            $rows = $json_data['predictions'];
            if(!empty($rows)){
                $this->nextToKen = '';
                foreach ($rows as $row){
                    switch ($lang){
                        case "zh-tw":
                            $row['description'] = $ZhService->zh_hans_to_zh_hant($row['description']);
                            break;
                        case "zh-cn":
                            $row['description'] = $ZhService->zh_hant_to_zh_hans_old($row['description']);
                            break;
                    }
                    $html.="<li style='$region'>";
                    $html.='<span>'.$row['description'].'</span>';
                    $html.='</li>';
                }
            }
        }

        return $html;
    }

    public function testSelect(){
        echo "start:<br>";
        set_time_limit(0);
        $this->_city="香港";
        $json_string = file_get_contents('D:\user.json');

        $data = json_decode($json_string, true);
        $mapModel=D("Map");
        $row=0;
        foreach ($data as $list){
            $mapModel->data($list)->add();
        }
        var_dump(count($data));

        $rs = $mapModel->addAll($data);
        var_dump($rs);
        echo "<br>end<br>";
    }

    //翻譯地址
    public function copyAddress(){
        set_time_limit(0);
        $mapModel=D("Map");
        $areaList = $mapModel->where(array("id"=>array('gt',220)))->select();
        $fanYiService = new FanYiService();
        if($areaList){
            foreach ($areaList as $item){
                $data["name_cn"]=$fanYiService->translate($item["name"],"cht","zh");
                $data["name_tw"]=$fanYiService->translate($item["name"],"auto","cht");
                $data["from_address_cn"]=$fanYiService->translate($item["from_name"],"cht","zh");
                $data["from_address_tw"]=$fanYiService->translate($item["from_name"],"auto","cht");
                $mapModel-> where(array("id"=>$item["id"]))->setField($data);
            }
        }
    }

    protected function HKSearchFor($oldText,$map_lang){
        $arr = array(
            "q"=>$oldText
        );
        $url = "https://www.als.ogcio.gov.hk/lookup?";
        $url.= http_build_query($arr);
        //var_dump($url);
        $headers = array(
            "content-type: application/json",
            "accept-language:".strlen($map_lang),
            "accept:application/json",
        );
        $data = $this->CurlRequest($url,null,$headers);
        $data = json_decode($data,true);
        return $data;
    }

    public function CurlRequest($url,$data=null,$header=null){
        //初始化浏览器
        $ch = curl_init();
        //设置浏览器，把参数url传到浏览器的设置当中
        curl_setopt($ch, CURLOPT_URL, $url);
        //以字符串形式返回到浏览器当中
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //禁止https协议验证域名，0就是禁止验证域名且兼容php5.6
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //禁止https协议验证ssl安全认证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //判断data是否有数据，如果有data数据传入那么就把curl的请求方式设置为POST请求方式
        if ( !empty($data) ) {
            //设置POST请求方式
            @curl_setopt($ch, CURLOPT_POST, true);
            //设置POST的数据包
            @curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        //设置header头
        if ( !empty($header) ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        //让curl发起请求
        $str = curl_exec($ch);
        //关闭curl浏览器
        curl_close($ch);
        //把请求回来的数据返回
        return $str;
    }

    public function getLanguageToStr($str){
        $lang = strtolower($this->_orderList["kehu_lang"]);
        if(is_numeric($str)){
            return $lang;
        }
        $pattern = '/[^\x00-\x80]/';
        if(preg_match($pattern,$str)){
            $strGbk = iconv("UTF-8", "GBK//IGNORE", $str);
            $strGb2312 = iconv("UTF-8", "GB2312//IGNORE", $str);
            if ($strGbk != $strGb2312 || $lang == "zh-tw") {
                return 'zh-tw';
            } else {
                return 'zh-cn';
            }
        }else{
            return 'en';
        }
    }

    //香港地區查詢
    public function textSearchToHK($text){
        $ZhService = new ZhConvertService();
        $region = strtolower($this->region);
        $html = "";
        $this->nextToKen = "";
        $text = trim($text);
        $lang = $this->getLanguageToStr($text);
        $lan = "en-us";
        if($lang=='en-us'||$lang=='en'){
            //$text = str_replace(" ","",$text);
            $map_lang = "en";
            $pre_name = "Eng";
        }else{
            $newText = $ZhService->zh_hans_to_zh_hant($text);
            if($newText == $text || $this->getLanguageToStr(1) == "zh-tw"){
                $lan = "zh-tw";
            }else{
                $lan = "zh-cn";
            }
            $text = $newText;
            $map_lang = "zh-Hant";
            $pre_name = "Chi";
        }
        //var_dump($lan);
        $data = $this->HKSearchFor($text,$map_lang);
        //var_dump($data);
        $arr = array();
        if($data){
            $data = $data["SuggestedAddress"];
            foreach ($data as $row){
                $row = $row["Address"]["PremisesAddress"][$pre_name."PremisesAddress"];
                $address_name = $row["BuildingName"];
                if(!empty($row[$pre_name."Estate"])){
                    if(!empty($row["BuildingName"])){
                        $address_name.="（";
                    }
                    $address_name.=$row[$pre_name."Estate"]["EstateName"];
                    if(!empty($row["BuildingName"])){
                        $address_name.="）";
                    }
                }
                if(empty($address_name)){
                    continue;
                }
                $address_name.=$this->arrToStrTwo($row[$pre_name."Block"],$pre_name);
                if(in_array($address_name,$arr)||count($arr)>20){
                    continue;
                }else{
                    $arr[] = $address_name;
                }
                $street = $this->arrToStr($row[$pre_name."Street"],$pre_name);//街道
                switch ($lan){
                    case "zh-tw":
                        $street = $ZhService->zh_hans_to_zh_hant($street);
                        $address_name = $ZhService->zh_hans_to_zh_hant($address_name);
                        $row["Region"] = $ZhService->zh_hans_to_zh_hant($row["Region"]);
                        break;
                    case "zh-cn":
                        $street = $ZhService->zh_hant_to_zh_hans($street);
                        $address_name = $ZhService->zh_hant_to_zh_hans($address_name);
                        $row["Region"] = $ZhService->zh_hant_to_zh_hans($row["Region"]);
                        break;
                }
                $html.="<li style='$region'>";
                $html.="<span>".$address_name."</span>";
                if($pre_name =='Eng'){
                    $html.="<small>".$street."</small>";
                    $html.="<small>".$row["Region"]."</small>" ;
                }else{
                    $html.="<small>".$row["Region"]."</small>" ;
                    $html.="<small>".$street."</small>";
                }
                $html.='</li>';
            }
        }
        return $html;
    }

    protected function arrToStrTwo($arr,$str='Chi'){
        if(is_array($arr)){
            if($str=='Eng'){
                return " ".$arr["BlockDescriptor"]." ".$arr["BlockNo"];
            }else{
                return $arr["BlockNo"].$arr["BlockDescriptor"];
            }
        }
        return $arr;
    }

    protected function arrToStr($arr,$str='Chi'){
        if(is_array($arr)){
            if($str=='Eng'){
                return " ".$arr["BuildingNoFrom"]." ".$arr["StreetName"];
            }else{
                return implode("",$arr).L("number");
            }
        }
        return $arr;
    }

    //搜索地區
    public function mapTestSelect($keywords="",$page=1){
        $page = intval($page);
        $key = $this->_key;
        if (empty($keywords)){
            $keywords = $this->_city;
        }
        $url = "http://restapi.amap.com/v3/config/district?keywords=$keywords&subdistrict=1&key=$key&page=$page";
        //var_dump($url);
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        if ($json_data->status == 1){
            $count = $json_data->count;
            $rows = $json_data->districts;
            if(!empty($rows)){
                foreach ($rows as $row){
                    if($row->level == "district"){
                        //附近搜索
                        $this->mapTestRound($row->adcode,$row->center);
                    }else{
                        $lists = $row->districts;
                        if(!empty($lists)){
                            foreach ($lists as $list){
                                $this->mapTestSelect($list->adcode);
                            }
                        }
                    }
                }

                if($count>$page*20){
                    $this->mapTestSelect($keywords,$page+1);
                }
            }
        }else{
            echo "<br>keywords:$keywords,page:$page"."<br>";
        }

        return true;
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
    public function mapTestRound($adCode,$location="",$radius = 3000,$page=1){
        $text = $adCode."&".$location."&".$page;
        if(array_search($text,$this->validateArr)){
            var_dump($this->validateArr);
            echo "<br>";
            echo $text;
            die();
        }else{
            $this->validateArr[] = $text;
        }
        $mapModel = D("Map");
        $page = intval($page);
        $key = $this->_key;
        //$location = "116.456299,39.960767";
        $types = implode("|",$this->_types);
        $url = "http://restapi.amap.com/v3/place/around?key=$key&location=$location&output=JSON&radius=$radius&types=$types&page=$page";
        $url.="&city=$adCode";
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        if ($json_data->status == 1){
            $count = $json_data->count;
            $rows = $json_data->pois;
            if(!empty($rows)){
                foreach ($rows as $row){
                    $data = array(
                        "name"=>$row->name,
                        "test_name"=>$row->name,
                        "from_name"=>$row->address,
                        "amap_id"=>$row->id,
                        "qcode"=>$row->pname,
                        "citycode"=>$row->cityname,
                        "acode"=>$row->adname,
                    );
                    $this->addArr[] = $data;
                    if($mapModel->create($data,1)){
                        $mapModel->add();
                    }
                }


                if($count>$page*20){
                    //echo "<br>page:$page"."<br>";
                    $this->mapTestRound($adCode,$location,3000,$page+1);
                }
            }
        }else{
            echo "<br>error:$adCode,page:$page,location:$location"."<br>";
        }

        return true;
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
            $this->region = $rs["web_prefix"];
            if($this->_orderList["region_name"]!="中国"){
                $this->_city = $this->_orderList["region_name"];
            }else{
                $this->region = "cn";
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