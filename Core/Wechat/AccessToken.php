<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-19 11:19:04 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-22 17:52:28
 */
namespace Core\Wechat;
use Help\Request as Request;
class AccessToken{
    private $appid;
    private $secret;
    
    public function _Assignment($appid,$secret){
        $this->appid = $appid;
        $this->secret = $secret;
    }

    public function _getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}"; 
        $data = Request::_HttpRequest($url);
        var_dump($data);
    }
}

