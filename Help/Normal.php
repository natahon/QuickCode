<?php
/**
 * Created by PhpStorm.
 * User: YingBin Xia
 * Date: 2019/10/10
 * Time: 17:00
 */
namespace Help;
class Normal{
    //post,get
    public static function curl_request($url,$method='get',$data=null,$https=true){
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        if($https === true){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        }
        if($method === 'post'){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    //post
    public static function externalRequestPost($url,$data = array(), $contentType = 'application/x-www-form-urlencoded'){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,  $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl); 
        if (curl_errno($curl)) {
            return 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        $data = json_decode($tmpInfo);
        return $data;
    }
}