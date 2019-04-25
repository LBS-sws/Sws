<?php
//驗證密碼格式
function checkPwd($str){
    $exp = "/^[a-zA-Z0-9]{4,15}$/";
    if(preg_match($exp,$str)){
        return true;
    }else{
        return false;
    }
}

//驗證用戶名格式
function checkName($str){
    $exp = "/^[\x{4e00}-\x{9fa5}a-z A-Z0-9]{2,50}$/u";
    if(preg_match($exp,$str)){
        return true;
    }else{
        return false;
    }
}
//驗證城市是否存在  true(存在)  false(不存在)
function checkCity($city_id){
    $user = session("user");
    $map["id"]= array(array('in',$user["city_auth"]),array('eq',$city_id));
    //$map['id']=$city_id;
    $rs = D("City")->where($map)->find();
    if($rs){
        return true;
    }else{
        return false;
    }
}
//驗證是否是數字
function checkNumber($num){
    if(is_numeric($num)){
        return true;
    }else{
        return false;
    }
}

/*//驗證手機號碼格式
function checkPhone($str){
    $exp = "/^1[1-9][0-9]{9}$/";
    if(preg_match($exp,$str)){
        return true;
    }else{
        return false;
    }
}*/
//驗證手機號碼格式
function checkPhone($str){
    $exp = "/(^[0-9]{8}$)|(^1[3|5|7|8]\d{9}$)/";
    if(preg_match($exp,$str)){
        return true;
    }else{
        return false;
    }
}

//驗證郵箱格式
function checkEmail($str){
    if(!empty($str)){
        $exp = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if(preg_match($exp,$str)){
            return true;
        }else{
            return false;
        }
    }else{
        return true;
    }
}

//密碼加密
function setMD5Pwd($pwd){
    $str = C('DB_PREFIX');
    return md5($str.$pwd);
}

//html安全編碼
function strToHtmlspecialchars($str){
    return htmlspecialchars($str);
}
function randomStrAZ() {
    $arr = array("A","B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L","M", "N", "O","P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y","Z");
    $rand_key = array_rand($arr,1);
    return $arr[$rand_key];
}
function codeAZAndInt($int) {
    $int = intval($int);
    $arr = array("A","B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L","M", "N", "O","P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y","Z");
    $i = floor($int/99999999);
    return $arr[$i];
}
//獲取郵箱提示列表
function geiEmailHintList(){
    $emailList = array(
        0=>L("off"),//關閉
        1=>L("on"),//開啟
        2=>L("general_business"),//一般害蟲提示
        3=>L("special_business")//特殊害蟲提示
    );
    return $emailList;
}
//自動生成訂單編號
function strToCodeLength($str,$prefix=""){
    $str++;
    $str = strval($str);
    $code = "";
    for($i = 0;$i < 8-strlen($str);$i++){
        $code.="0";
    }
    $code .= $str;
    return $prefix.codeAZAndInt($str).$code;
}

//獲取左側菜單的列表
function getMenuLeftListToAuth(){
    $list = C('MENU');
    $arr= array();
    foreach ($list as $val){
        $arr[$val["action"]] = $val["auth"];
    }
    return $arr;
}

//獲取室內面積
function getIndoorList(){
    $arr= array(""=>0);
    $num = 500;
    for($i = 0;$i<36;$i++){
        $sum = $i*100+$num;
        if($i == 0){
            $arr[$sum] = "<=".$sum;
        }else{
            $arr[$sum] = "~".$sum;
        }
    }
    return $arr;
}
//獲取室外面積
function getOutdoorList(){
    $arr= array(""=>0);
    $num = 100;
    for($i = 0;$i<40;$i++){
        $sum = $i*100+$num;
        $arr[$sum] = "~".$sum;
    }
    return $arr;
}

//輸出室內單位
function getDoorUnit($str=0){
    $arr=array(
        "mi"=>L("mi"),
        "chi"=>L("chi"),
        "ping"=>L("ping")
    );
    if(array_key_exists($str,$arr)){
        return $arr[$str];
    }else{
        return $arr;
    }
}

//驗證害蟲是否存在
function validateBusinessList($arr,$type = 2){
    if(empty($arr)){
        return false;
    }
    if(is_array($arr)){
        $businessModel = D("Business");
        foreach ($arr as $bus_id){
            $map["id"] = $bus_id;
            if($type !== 2){
                $map["type"] = $type;
            }
            $rs = $businessModel->where($map)->find();
            if(!$rs){
                return false;
            }
        }
    }else{
        return false;
    }
    return true;
}

//驗證室內單位
function checkDoorUnit($str){
    $str = getDoorUnit($str);
    if(empty($str)){
        return false;
    }else{
        return true;
    }
}

//獲取網站的尾綴
function getWebPrefix(){
    $webServer = $_SERVER["SERVER_NAME"];
    $prefix = explode(".",$webServer);
    if(count($prefix) <= 2){
        $prefix = 'cn';
    }else{
        $prefix = end($prefix);
    }
    return $prefix;
}

//根據客戶郵件獲取郵件關聯的官網
function getEmailImportToEmail($email){
    if(!empty($email)){
        $prefix = end(explode("@",$email));
        if(!empty($prefix)){
            $emailModel = D("Email");
            $map["email_prefix"] = $prefix;
            $rs = $emailModel->where($map)->find();
            if($rs){
                return $rs["email"];
            }
        }
    }
    return "";
}

//獲取名字（中、繁、英）的尾綴
function getNamePrefix($lang=""){
    if (empty($lang)){
        $lang = strtolower(LANG_SET);
    }else{
        $lang = strtolower($lang);
    }
    switch ($lang){
        case "en-us":
            $prefix = "_us";
            break;
        case "zh-cn":
            $prefix = "";
            break;
        case "zh-tw":
            $prefix = "_tw";
            break;
        default:
            $prefix = "";
    }
    return $prefix;
}

//獲取名字（中、繁、英）的尾綴
function getNamePrefixToStr($str){
    switch (strtolower($str)){
        case "en-us":
            $prefix = "_us";
            break;
        case "zh-cn":
            $prefix = "";
            break;
        case "zh-tw":
            $prefix = "_tw";
            break;
        default:
            $prefix = "";
    }
    return $prefix;
}
//根據不用語言輸出不同的字符串
function getTopStrToLang(){
    switch (strtolower(LANG_SET)){
        case "en-us":
            $str = "en";
            break;
        case "zh-cn":
            $str = "sc";
            break;
        case "zh-tw":
            $str = "tc";
            break;
        default:
            $str = "sc";
    }
    return $str;
}

//根據狀態顯示不同顏色
function getStyleToStatus($status){
    switch ($status){
        case "send": //待處理
            return "warning";
        case "service":  //已接受
            return "success";
        case "modified": //已修改
            return "orange";
        case "finish": //完成
            return "active";
        case "reject": //拒絕
            return "danger";
        case "hesitate":  //需要技術員聯絡
            return "info";
        case "guest_service":  //已接受（客戶)
            return "orange";
        default:
            return "";
    }
}
/**
 * 邮件发送函数
 */
function sendMail($to, $title, $content,$code=0,$pdf=false,$pdfTitle="") {
    $emailConfig = C('define');
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $email_service = $emailConfig["Mail"];
    if(!empty($code)){
        $title.=" - ".$code;
    }
    if (strpos($content,'<img')!==false){
        $mail->addembeddedimage("Public/sws/img/".L("img_1_1"),'titleLog');
    }

    if($pdf){
        if(empty($pdfTitle)){
            $mail -> AddAttachment("Public/sws/pdf/".$code.".pdf",$title.'.pdf');//附件的路径和附件名称
        }else{
            $pdfTitle.=" - ".$code;
            $mail -> AddAttachment("Public/sws/pdf/".$code.".pdf",$pdfTitle.'.pdf');//附件的路径和附件名称
        }
    }

    $mail->Host=$email_service['MAIL_HOST']; //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->Port = $email_service['MAIL_PORT'];
    //$mail->SMTPDebug = 1;//啟用調試模式
    $mail->Username = $email_service['MAIL_USERNAME']; //你的邮箱名
    $mail->Password = $email_service['MAIL_PASSWORD']; //邮箱密码
    $mail->From = $email_service['MAIL_FROM']; //发件人地址（也就是你的邮箱地址）

    $mail->FromName = L("email_sws"); //发件人姓名
    $mail->SMTPAuth = $emailConfig['MAIL_SMTPAUTH']; //启用smtp认证
    $mail->AddAddress($to,$to);
    $mail->IsHTML($emailConfig['MAIL_ISHTML']); // 是否HTML格式邮件
    $mail->CharSet=$emailConfig['MAIL_CHARSET']; //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示

    //var_dump($mail->ErrorInfo);
    return $mail->Send();
}

//統一計算訂單的總價
function setOrderTotalPrice(&$orderList){
    if(empty($orderList["kehu_lang"])){
        $orderList["kehu_lang"] = "";
    }
    $orderList["business_id"] = ",";
    $prefix = getNamePrefix($orderList["kehu_lang"]);
    $business_list_name = array();
    if(empty($orderList["area_id"])){ //其它區域價格設定
        $orderList["area_price"] = $orderList["other_price"];
        $orderList["min_price"] = $orderList["other_min"];
    }
    $businessList = $orderList["business_list"];
    $minPrice =floatval($orderList["min_price"]); //實際的最低價格 = 交通費用 + 最低價格
    if(empty($orderList["total_price"])){
        $totalPrice = 0;//初始設定為交通費
        if($orderList["calculation"]==1){ //害蟲合併計算
            foreach ($businessList as $key =>$business){
                $business_list_name[] =$business["name".$prefix];
                $orderList["business_id"] .=$business["bus_id"].",";
                $price=floatval($business["price"])*floatval($orderList["door_in"]);
                $orderList["business_list"][$key]["total_price"] = $price;
                $totalPrice+=$price;
            }
            $totalPrice = $totalPrice<$minPrice?$minPrice:$totalPrice;
        }else{ //害蟲分開計算
            foreach ($businessList as $key =>$business){
                $business_list_name[] =$business["name".$prefix];
                $orderList["business_id"] .=$business["bus_id"].",";
                $price=floatval($business["price"])*floatval($orderList["door_in"]);
                $price = $price<$minPrice?$minPrice:$price;
                $orderList["business_list"][$key]["total_price"] = $price;
                $totalPrice+=$price;
            }
        }
        $totalPrice += floatval($orderList["area_price"]);//交通費
    }else{
        foreach ($businessList as $key =>$business){
            $business_list_name[] =$business["name".$prefix];
            $orderList["business_id"] .=$business["bus_id"].",";
            $price=floatval($business["price"])*floatval($orderList["door_in"]);
            $orderList["business_list"][$key]["total_price"] = $price;
        }
        $totalPrice = $orderList["total_price"];
    }
    $orderList['business_list_name'] = implode(",",$business_list_name);
    $orderList['total_price'] = sprintf("%.2f", $totalPrice);
}

//去除字符串的第一個字符和最後一個字符
function removeOneAndEnd($str){
    if (empty($str)){
        return "";
    }elseif(strlen($str)<=2){
        return "";
    }else{
        return substr($str,1,-1);
    }
}
