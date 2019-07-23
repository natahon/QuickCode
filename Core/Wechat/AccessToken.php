<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-19 11:19:04 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-23 18:03:52
 */
namespace Core\Wechat;
use Help\Request as Request;
use Pub\ReturnCode as RCe;
class AccessToken{
    private $appid;
    private $secret;
    
    public function _Assignment($appid,$secret){
        $this->appid = $appid;
        $this->secret = $secret;
    }
    
    /**
	 * @apiUrl ?c=site&a=entry&do=Deliver&m=group
     * @apiWay POST
	 * @apiVersion 1.0.0
	 * @apiGroup Group-Tools
	 * @apiName 获取AccessToken
	 * @apiParam null 无 无
	 * @apiSuccessExample {html} 成功: {"code": "3001","data": {"total": "80","data": [],"pagesize": 10,"page": "9"},"other": "","msg": "操作成功"}
     * @apiSuccessExample {html} 失败: {"code": "40013","data": '',"other": "","msg": "不合法的 AppID ，请开发者检查 AppID 的正确性，避免异常字符，注意大小写"}
     * 
	 */
    public function _getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}"; 
        $data = Request::_HttpRequest($url);
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

