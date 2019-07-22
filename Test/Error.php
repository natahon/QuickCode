<?php

function getErrorMsg($error_level,$message,$file,$line){
    $EXIT =FALSE;
    switch($error_level){
        //提醒级别
        case E_NOTICE:
        case E_USER_NOTICE:
        $error_type = 'Notice';
        break;
        //警告级别
        case E_WARNING:
        case E_USER_WARNING:
        $error_type='warning';
        break;
        //错误级别
        case E_ERROR:
        case E_USER_ERROR:
        $error_type='Fatal Error';
        $EXIT = TRUE;
        break;
        //其他未知错误
        default:
        $error_type='Unknown';
        $EXIT = TRUE;
        break; 
    }
    printf("<font color='#FF0000'><b>%s</b></font>:%s in<b>%s</b> on line <b>%d</b><br>\n",$error_type,$message,$file,$line); 
}