<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-19 11:19:04 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-19 11:30:55
 */
namespace Code\Wechat;

class AcessToken{
    private $appid;
    private $secret;
    
    public function _Assignment($appid,$secret){
        $this->appid = $appid;
        $this->secret = $secret;
    }

    public static function _getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}"; 
    }
}

