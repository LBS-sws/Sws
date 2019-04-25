<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/7/27 0027
 * Time: 下午 2:56
 */
namespace Sws\Logic;
Vendor('TCPDF.tcpdf');
class PdfLogic extends \TCPDF {

    function Header() //设定页眉
    {
        $this->SetFillColorArray(array(231,56,40));
        $this->writeHTMLCell(210, 5, 0,15, " ", 0, 1, true, true, '', true);
        $this->writeHTMLCell(210, 5, 0,278, " ", 0, 1, true, true, '', true);
        $this->SetFillColorArray(array(236,104,42));
        $this->writeHTMLCell(210, 5, 0,20, " ", 0, 1, true, true, '', true);
        $this->writeHTMLCell(210, 5, 0,283, " ", 0, 1, true, true, '', true);
        $this->SetFillColorArray(array(255,255,255));
        $this->writeHTMLCell(32, 40, 9,5, " ", 0, 1, true, true, '', true);
        $this->writeHTMLCell(56, 18, 147,274, " ", 0, 1, true, true, '', true);
        $this->Image('Public/sws/img/'.L("img_1_1"),10,5,30,40);
        $this->Image('Public/sws/img/'.L("img_1_2"),45,10,0,10);
    }
    function Footer() //设定页脚
    {
        //$this->SetY(-15);
        $this->Image('Public/sws/img/'.L("img_2"),150,274,50,18);
    }

}