<?php

namespace Sws\Common;
use Think\Controller;
class BaseController extends Controller{

    //判斷是否登錄
    public function _initialize(){
        $oldUrl = base64_encode($_SERVER["REQUEST_URI"]);
        $oldUrl = strtr($oldUrl,"/","唷");
        defined('CURRENT_URL') or define('CURRENT_URL',$oldUrl);
        if(!session("?user")){
            $this->redirect("/sws/login/index",array("login"=>CURRENT_URL));
        }else{
            $userSession = session("user");
            $menuAuth = getMenuLeftListToAuth();

            $top_title = session('TOP_TITLE');
            if(!empty($top_title)){
                session('TOP_TITLE',null);
                $this->assign("top_title",$top_title);
            }
            if (strpos($userSession["auth"],$menuAuth[CONTROLLER_NAME])!==false || CONTROLLER_NAME == "Index"){
                session(null);
                session("user",$userSession);
            }else{
                $this->error(L("error_auth"),"/sws/index/index",5);
                $this->redirect("/sws/index/index");
            }
        }
    }

    public function resetSession(){
        $userView = D("UserView");
        $userSession = session("user");
        $user = $userView->where(array("id"=>$userSession["id"]))->find();
        unset($user["password"]);
        session('user',$user);
    }

    /**
     * 空操作 跳转
     * */
    public function _empty(){
        //abort();
        $this->error("方法不存在");
    }
}