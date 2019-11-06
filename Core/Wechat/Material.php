<?php
/**
 * Created by PhpStorm.
 * User: YingBin Xia
 * Date: 2019/10/10
 * Time: 15:55
 */
namespace Core\Wechat;
use Help\Normal as Normal;
use Pub\ReturnCode as RCe;
use Core\Wechat\AccessToken as Token;
class Material{

    private $token;    

    public function returnArr($param){
        $material = array(
            'upload' => 'https://api.weixin.qq.com/cgi-bin/media/upload',
            'get' => 'https://api.weixin.qq.com/cgi-bin/media/get',
            'add_news' => 'https://api.weixin.qq.com/cgi-bin/material/add_news',
            'get_material' => 'https://api.weixin.qq.com/cgi-bin/material/get_material',
            'del_material' => 'https://api.weixin.qq.com/cgi-bin/material/del_material',
            'update_news' => 'https://api.weixin.qq.com/cgi-bin/material/update_news',
            'get_materialcount' => 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount',
            'batchget_material' => 'https://api.weixin.qq.com/cgi-bin/material/batchget_material'
        );
        return $material[$param];
    }

    public function __construct(){
        $this->token = new Token();
    }

    public function __call($method,$values){
        $method = strtolower($method);
        if(!$this->returnArr($method)){
            echo '调用类'.get_class($this).'中的方法'.$method.'()不存在';
        }
    }

    public function batchget_material(){
        $accessToekn = '26_OY8uMdQQqxHwtWKMG8l-2QfYMoe9N0t7Ag5pFUK0ZSYVD5niumq5B9E2_P1SZ2FiOoNw62oFOeZEtaCItd_Q2XfHZrBc0oOqv7aJxKOW7tK61wnZv0u5BfolcKxeipeA_pXnsaL6jImHVGw-ZRJiACAKCU';
        $url = $this->returnArr(__FUNCTION__ )."?access_token=".$accessToekn;
        $method = 'get';
        $array = array(
            "type" => 'news',
            "offset" => 0,
            "count" => 20
        );
        $this->getDataByUrl($url,$method,$array,true);
    }

    private function getDataByUrl($url,$method='get',$data=null,$https=true){
        $data = Normal::curl_request($url,$method,$data,$https);
        var_dump($data);
        if(isset($data['content'])){
            $content = json_decode($data['content'],true);
            if(isset($content['errcode'])){
                RCe::returnJsonData($content['errcode']);
            }else{
                RCe::returnJsonData('3001',$content);
            }
        }
    }
}