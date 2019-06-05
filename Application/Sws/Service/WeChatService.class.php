<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 * 主要用於管理員操作訂單
 */
namespace Sws\Service;
class WeChatService{
    protected $AppID = "";
    protected $AppSecret = "";

    protected $access_token;
    protected $jsapi_ticket;
    protected $expires_time;

    protected $address_key = "GWJBZ-XDRWI-THQGS-55BL7-ZKYDZ-7LBKU";//騰訊地圖的KEY
    protected $address_key_secret = "StPTfaUYLLMf96SHOQEfotWcZefJq6Ky";//騰訊地圖的簽名加密

    public function __construct(){
        $this->AppID = "wxfa88392cf79fd768";
        $this->AppSecret = "2cb0c35e6c837a955223eaee055959ee";

        //1. 本地读取
        $fileUrl = THINK_PATH.'../Public/Uploads/access_token.json';
        $res = file_get_contents($fileUrl);
        $result = json_decode($res, true);
        $this->expires_time = $result["expires_time"];
        $this->jsapi_ticket = $result["jsapi_ticket"];
        $this->access_token = $result["access_token"];

        if (time() > ($this->expires_time + 7000)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->AppID."&secret=".$this->AppSecret;
            $result = $this->http_request($url,"",true,false);
            $this->access_token = $result["access_token"];
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$this->access_token;
            $result = $this->http_request($url,"",true,false);
            $this->jsapi_ticket = $result["ticket"];
            $this->expires_time = time();
            //1. 本地写入
            file_put_contents($fileUrl, '{"access_token": "'.$this->access_token.'","jsapi_ticket": "'.$this->jsapi_ticket.'", "expires_time": '.$this->expires_time.'}');
        }
    }

    public function getToken(){
        return $this->access_token;
    }

    public function getTime(){
        return $this->expires_time;
    }

    public function getSignPackage() {
        $jsapiTicket = $this->jsapi_ticket;

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->getCheckCode();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);
        $signPackage = array(
            "debug"     => false,
            "appId"     => $this->AppID,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
            "jsApiList" => ['getLocation',
                'updateAppMessageShareData',
                'updateTimelineShareData',
                'onMenuShareAppMessage',
                'onMenuShareWeibo',
                'openLocation'],
            //"rawString" => $string
            //"url"       => $url,
        );
        return $signPackage;
    }

    //自定義菜單
    public function createMenu(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        $dataMenu = array(
            "button"=>array(
                array("name"=>"汇服务","sub_button"=>array(
                    array("name"=>"绑定订单","type"=>"view","url"=>U("/sws/api/bindingOrder","",false,true)),
                    array("name"=>"预约灭虫","type"=>"view","url"=>U("/sws/api/newOrder","",false,true)),
                    array("name"=>"我的订单","type"=>"view","url"=>U("/sws/api/orderList","",false,true)),
                )),
                array("name"=>"网上缴费","type"=>"view","url"=>U("/sws/api/orderList",array("type"=>"pending"),false,true)),
                array("name"=>"优惠活动","type"=>"click","key"=>"v_activity")
            )
        );
        $dataMenu = json_encode($dataMenu,JSON_UNESCAPED_UNICODE);
        $result = $this->http_request($url,$dataMenu);
        var_dump($result);
    }

    //設置訂閱號的行業
    public function setIndustry(){
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=".$this->access_token;
        $dataMenu = array(
            "industry_id1"=>41,
            "industry_id2"=>3,
        );
        $dataMenu = json_encode($dataMenu,JSON_UNESCAPED_UNICODE);
        $result = $this->http_request($url,$dataMenu);
        var_dump($result);
    }
/*
    { {first.DATA} }
    关键词1：{ {keyword1.DATA} }
    关键词2：{ {keyword2.DATA} }
    关键词3：{ {keyword3.DATA} }
    { {remark.DATA} }
*/
    //發送模板消息
    public function sendTemplate($openid,$template_id,$toUrl,$data=array()){
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->access_token;
        $dataMenu = array(
            "touser"=>$openid,//接受user的id（openid）
            "template_id"=>$template_id,
            "url"=>$toUrl,
            "data"=>$data,
        );
        $dataMenu = json_encode($dataMenu,JSON_UNESCAPED_UNICODE);
        $result = $this->http_request($url,$dataMenu);
/*        array(
            "first"=>array(
                "value"=>"恭喜您下單成功！",
                "color"=>"#173177"
            ),
            "value1"=>array(
                "value"=>"測試",
                "color"=>"#173177"
            ),
        )*/
        return $result;
    }

    //訂閱公众号(暫時無用)
    public function subscribeLbs(){
        $checkCode = $this->getCheckCode();
        session("checkCode",$checkCode);
        $param = array(
            'action'=>'get_confirm',
            'appid'=>$this->AppID,
            'scene'=>100,
            'template_id'=>'uBsarmHTaQHQVi8EOTQk7Pa6F-bQw28gppk2Ghb7oOk',
            'redirect_url'=>U("/sws/api/test","",false,true),
            'reserved'=>$checkCode,
        );
        //将数组拼接成url地址参数
        $paramUrl = http_build_query($param , '' , '&');
        $url = "https://mp.weixin.qq.com/mp/subscribemsg?$paramUrl#wechat_redirect";
        return $url;
    }

    //微信登錄
    public function authorize($url){
        $checkCode = $this->getCheckCode();
        session("checkCode",$checkCode);
        $param = array(
            'appid'=>$this->AppID,
            'redirect_uri'=>$url,
            'response_type'=>"code",
            'scope'=>"snsapi_userinfo",
            'state'=>$checkCode,
        );
        //将数组拼接成url地址参数
        $paramUrl = http_build_query($param , '' , '&');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?$paramUrl#wechat_redirect";
        return $url;
    }

    //微信登錄(code换取access_token)
    public function getWebAccessToken($code){
        $access = session("access_token");
        if(empty($access)){ //獲取access（未有記錄)
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->AppID."&secret=".$this->AppSecret."&code=$code&grant_type=authorization_code";
        }elseif ($access["expires_in"]+7000<time()){ //access已過期
            $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$this->AppID."&grant_type=refresh_token&refresh_token=".$access["refresh_token"];
        }
        if(!empty($url)){
            $access = $this->http_request($url,"",true,false);
            if(key_exists("errcode",$access)){
                session("access_token",null);
            }else{
                $access["expires_in"] = time();
                session("access_token",$access);
            }
        }
        return $access;
    }

    //獲取單用戶的基本信息
    public function getThisUser($code){
        $access = $this->getWebAccessToken($code);
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$access["openid"]."&lang=zh_CN";
        //如果关注了公众号，subscribe为1。如果没有关注公众号subscribe为0。
        $result = $this->http_request($url,"",true,false);
        return $result;
    }

    //根據經緯度獲取地址 location=lat<纬度>,lng<经度>
    public function getAddressToLocation($location){
        $key = "/ws/geocoder/v1/?key=".$this->address_key."&location=$location".$this->address_key_secret;
        $sig = md5($key);//&get_poi=0
        $url = "https://apis.map.qq.com/ws/geocoder/v1/?location=$location&key=".$this->address_key."&sig=$sig";
        //如果关注了公众号，subscribe为1。如果没有关注公众号subscribe为0。
        $result = $this->http_request($url,"",true,false);
        if($result["result"]!==null){
            return array(
                "address"=>$result["result"]["address"],
                "nation"=>$result["result"]["address_component"]["nation"],//中國
                "region"=>$result["result"]["address_component"]["province"],//省份
                "city"=>$result["result"]["address_component"]["city"],//城市
                "area"=>$result["result"]["address_component"]["district"],//區域 可能为空字串
            );
        }
        return $result["result"];
    }

    protected function strToArr($str){
        $arr = array("-1*");
        //特别行政区 省  市 区
        $list = array("特别行政区","省","市","区");
        if (!empty($str)){
            $arr[] = $str;
            foreach ($list as $item){
                $expStr = explode($item,$str);
                $expStr = current($expStr);
                if(!in_array($expStr,$arr)){
                    $arr[] = $expStr;
                }
                $expStr = str_replace("洲","州",$expStr);
                if(!in_array($expStr,$arr)){
                    $arr[] = $expStr;
                }
            }
        }
        return $arr;
    }

    public function resetArea(&$data,$cityList){
        $areaModel = D("Area");
        $area_id = $areaModel->where(array("area_name"=>array("in",$this->strToArr($data["area"]))))->getField("id");
        if($area_id){
            $data["area"] = $area_id;
        }elseif ($cityList["other_open"] == 1){
            $data["area"] = 0;
        }else{
            $data["area"] = $areaModel->where(array("city_id"=>$cityList["id"]))->getField("id");
        }
    }

    //查詢城市id并重置（定位后城市分析)
    public function resetCity($data){
        if($data){
            $regionModel = D("Region");
            $cityModel = D("City");
            $areaModel = D("Area");
            $region_id = $regionModel->where(array("region_name"=>array("in",$this->strToArr($data["region"]))))->getField("id");
            if ($region_id){
                $data["region"] = $region_id;
                $cityList = $cityModel->where(array("city_name"=>array("in",$this->strToArr($data["city"]))))->find();
                if($cityList){
                    $data["city"] = $cityList["id"];
                    $this->resetArea($data,$cityList);
                }else{
                    $cityList = $cityModel->where(array("region_id"=>$region_id))->find();
                    $data["city"] = $cityList["id"];
                    if($cityList["other_open"] == 1){
                        $data["area"] = 0;
                    }else{
                        $data["area"] = $areaModel->where(array("city_id"=>$cityList["id"]))->getField("id");
                    }
                }
            }else{
                $cityList = $cityModel->where(array("city_name"=>array("in",$this->strToArr($data["city"]))))->find();
                if($cityList){
                    $data["region"] = $cityList["region_id"];
                    $data["city"] = $cityList["id"];
                    $this->resetArea($data,$cityList);
                }else{
                    return false; //城市查詢為空
                }
            }
        }
        return $data;
    }

    //下單成功發送模板消息
    public function sendTemplateToOrder($data){
        $openid = session("access_token")["openid"];
        //$template_id = "LlcyarEdDh-9Z5l6BN8MKS9Fh5bx_Ji_eZNcd2LCOtI";
        $template_id = "m2LNeWAp2vRCrqL7VZ1RXdoRBl0TLY_eWWsThB_jzMY";
        foreach ($data["template"] as $list){
            $toUrl = U("/sws/api/editOrder",array("id"=>$list["sta_id"]),false,true);
            $arr = array(
                "first"=>array(
                    "value"=>"恭喜您下單成功！",
                    "color"=>"#173177"
                ),
                "value1"=>array( //用戶姓名
                    "value"=>$data["order_name"],
                    "color"=>"#173177"
                ),
                "value2"=>array( //訂單編號
                    "value"=>$list["s_code"],
                    "color"=>"#173177"
                ),
                "value3"=>array( //害蟲信息
                    "value"=>implode(",",$list["name"]),
                    "color"=>"#173177"
                ),
                "value4"=>array( //下單時間
                    "value"=>$data["lcd"],
                    "color"=>"#173177"
                )
            );
            $this->sendTemplate($openid,$template_id,$toUrl,$arr);
        }
    }

    //訂單發送變化后發送信息
    public function sendTemplateToOrderChange($data,$openid){
        $template_id = "m2LNeWAp2vRCrqL7VZ1RXdoRBl0TLY_eWWsThB_jzMY";
        $toUrl = U("/sws/api/editOrder",array("id"=>$data["sta_id"]),false,true);
        $arr = array(
            "first"=>array(
                "value"=>$data["change_title"],
                "color"=>"#173177"
            ),
            "value1"=>array( //用戶姓名
                "value"=>$data["order_name"],
                "color"=>"#173177"
            ),
            "value2"=>array( //訂單編號
                "value"=>$data["s_code"],
                "color"=>"#173177"
            ),
            "value3"=>array( //害蟲信息
                "value"=>implode(",",$data["name"]),
                "color"=>"#173177"
            ),
            "value4"=>array( //下單時間
                "value"=>$data["lcd"],
                "color"=>"#173177"
            )
        );
        $this->sendTemplate($openid,$template_id,$toUrl,$arr);
    }

    public function http_request($url, $data=null,$bool=true,$post=true) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        if($post){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, $bool);
        return $result;
    }

    protected function getCheckCode() {
        $arr = array("A","B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L","M", "N", "O","P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y","Z");
        $rand_key = array_rand($arr,3);
        return $arr[$rand_key[0]].$arr[$rand_key[1]].implode("",$rand_key).$arr[$rand_key[2]];
    }
}