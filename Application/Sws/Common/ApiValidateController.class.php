<?php

namespace Sws\Common;
use Sws\Service\WeChatService;
use Think\Controller;

class ApiValidateController extends Controller{

    private $notLoginArr = array("setindustry","createmenu","subscribelbs","wechatback","test","closewechat");
    public $_weChat;

    public function __construct(){
        $this->_weChat = new  WeChatService();
        parent::__construct();

    }

    //判斷Token是否正確
    public function _initialize(){
        //session("access_token",array("openid"=>"obKQEt2RpCpaWR5Fdgpksk9_BEP8"));
        $action = strtolower(ACTION_NAME);
        if(!in_array($action,$this->notLoginArr)){
            $access_token = session("access_token");
            $code = "";
            //判斷是否已經登錄
            if(empty($access_token)){ //沒有登錄
                $code = I("code","");
                $state = I("state","");
                $checkCode = session("checkCode");
                if(empty($code)||empty($state)||$checkCode!=$state){ //驗證驗證碼是否一致
                    $url = U(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME,"",true,true);
                    $url = $this->_weChat->authorize($url);
                    header("location:$url");
                    exit;
                }
            }
            $this->_weChat->getWebAccessToken($code);//驗證access是否過期，過期后自動更新
        }
    }
}