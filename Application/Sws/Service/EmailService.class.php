<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 * 主要用於客戶修改訂單
 */
namespace Sws\Service;
class EmailService{
    protected $_EMAIL;
    protected $_HTML;
    protected $_Address_to;
    protected $_Title;
    protected $_Config;
    protected $_Order_type;//訂單類型

    public function __construct($to, $title, $content,$order_type = 2) {
        Vendor('PHPMailer.PHPMailerAutoload');
        $this->_Config = C('define');
        $this->_Order_type = intval($order_type);
        $this->_HTML = $content;
        $this->_Address_to = $to;
        $this->_Title = $title;
        $this->_EMAIL = new \PHPMailer(); //实例化
        $this->_EMAIL->IsSMTP(); // 启用SMTP
        $email_service = $this->getService($to);

        $this->_EMAIL->Host=$email_service['MAIL_HOST']; //smtp服务器的名称（这里以QQ邮箱为例）
        $this->_EMAIL->Port = $email_service['MAIL_PORT'];
        //$mail->SMTPDebug = 1;啟用調試模式
        $this->_EMAIL->Username = $email_service['MAIL_USERNAME']; //你的邮箱名
        $this->_EMAIL->Password = $email_service['MAIL_PASSWORD']; //邮箱密码
        $this->_EMAIL->From = $email_service['MAIL_FROM']; //发件人地址（也就是你的邮箱地址）

        $this->_EMAIL->FromName = L("email_sws"); //发件人姓名
        $this->_EMAIL->SMTPAuth = $this->_Config['MAIL_SMTPAUTH']; //启用smtp认证
        $this->_EMAIL->IsHTML($this->_Config['MAIL_ISHTML']); // 是否HTML格式邮件
        $this->_EMAIL->CharSet=$this->_Config['MAIL_CHARSET']; //设置邮件编码

        $this->_EMAIL->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    }

    private function getService($email_prefix){
        $emailConfig =$this->_Config;
        $email_service = array();
        switch ($email_prefix){
            case "qq":
                if (!empty($emailConfig["Mail_qq"])){
                    $email_service = $emailConfig["Mail_qq"];
                    $this->_EMAIL->SMTPSecure = 'ssl';
                }
                break;
            case "163":
                if (!empty($emailConfig["Mail_163"])){
                    $email_service = $emailConfig["Mail_163"];
                }
                break;
        }
        if(empty($email_service)){
            $email_service = $emailConfig["Mail"];
        }
        return $email_service;
    }

    //添加html
    public function addHtmlToContent($html){
        $this->_HTML.=$html;
    }

    //獲取當前的html
    public function getEmailHtml(){
        return $this->_HTML;
    }
    //添加地址
    public function addAddress($arr){
        if(is_array($arr)){
            foreach ($arr as $email){
                $this->_EMAIL->AddAddress($email);
            }
        }elseif (!empty($arr)){
            $this->_EMAIL->AddAddress($arr);
        }
    }

    //添加附件
    public function addMent($url){
        $this->_EMAIL->AddAttachment($url); // 添加附件
    }

    //客戶新建、修改訂單后，管理員接受郵件
    public function setAdminToEmail($city_id,$sta_id,$type=''){
        $userModel = D("User");
        if($this->_Order_type === 0){ //特殊業務
            $where["email_hint"] = array('in','1,3');
        }elseif ($this->_Order_type === 1){ //普通業務
            $where["email_hint"] = array('in','1,2');
            if($type == 'b'){ //客戶要求聯繫
                $where["more_hint"] = 1;
                $where['_logic'] = 'or';
            }
        }else{
            $where["email_hint"] = array('neq',0);
        }
        $map['_complex'] = $where;
        $map["city_auth"] =  array('like',"%,$city_id,%");
        $userList = $userModel->where($map)->select();
        foreach ($userList as $user){
            if(!empty($user["email"])){
                $this->_EMAIL->AddAddress($user["email"]);
            }
        }
        if(empty($sta_id)){
            $url = U("/sws/order/index");
        }else{
            $url = U("/sws/order/detail",array("index"=>$sta_id));
        }
        $url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$url;
        $html = '<p><a target="_blank" style="color: #337ab7;margin-right:20px; " href="'.$url.'">'.L('btn_read').'</a></p>';
        $this->_HTML.=$html;
    }

    //客戶自己接受郵件
    public function setEmailHtml($orderList){
        $orderBusViewModel = D("OrderBusView");
        $prefix = getNamePrefix($orderList["kehu_lang"]);
        $html = "<p>".L("email_1")."：".$orderList["s_code"]."</p>";
        $html.="<p>".L("email_2")."：".$orderList["order_name"]."</p>";
        $html.="<p>".L("order_phone")."：".$orderList["phone"]."</p>";
        $html.="<p>".L("order_email")."：".$orderList["email"]."</p>";
        $html.="<p>".L("city_address")."：".$orderList["city_name".$prefix]." - ".$orderList["area_name".$prefix]."</p>";
        //客戶的害蟲列表
        $html.= "<p>".L("Infestation")."：";
        $map["order_id"] = $orderList["order_id"];
        $map["type"] = $orderList["s_type"];
        $orderList["business_list"] = $orderBusViewModel->where($map)->select();
        setOrderTotalPrice($orderList);
        $html.=$orderList["business_list_name"];
        $html.="</p>";
        $html.= "<p>".L("pdf_14")."：（".$orderList["currency_type"]."）".$orderList["total_price"]."</p>";
        $this->_HTML = $html;
    }
    /**
     * 邮件发送函数
     */
    public function sendMail() {
        if (!empty($this->_Address_to)){
            $this->_EMAIL->AddAddress($this->_Address_to,L("email_4"));
        }
        $this->_EMAIL->Subject =$this->_Title; //邮件主题
        $this->_EMAIL->Body = $this->_HTML; //邮件内容


        //$this->_EMAIL->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
        //$this->_HTML.='<img alt="helloweba" src="cid:my-attach">';
        return ($this->_EMAIL->Send());
    }
}