<?php
namespace xiejianxuan5\sendcloud;
/**
 * SendCloud（https://sendcloud.sohu.com）
 */
class SendCloud
{
    public $apiUser = null;
    public $apiKey = null;
    public $sendcloudUrl = 'http://api.sendcloud.net/apiv2/';
    public $response = null;
    public $isSucceed = false;
    public $responseStatusCode;
    public $responseMessage;
    public $responseInfo;
    /*
    public function __construct($apiUser, $apiKey) {
        $this->apiUser = $apiUser;
        $this->apiKey  = $apiKey;
    }
    */
   
    /**
     * @param  [type]
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public function sendRequest($module, $action, $postData){
        $requestUrl = $this->sendcloudUrl . $module . '/' . $action;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $this->response = curl_exec($ch);
        if($this->response === false){
            throw new SendCloudException("CURL错误: " . curl_error($ch));
        }
        curl_close($ch);
        $this->analyResponse();
        return $this->responseStatusCode == '200';
    }

    public function analyResponse(){
        if(!$this->response){
            throw new SendCloudException("没有成功返回信息");
        }
        $responseArray = json_decode($this->response, true);
        $this->responseStatusCode = isset($responseArray['statusCode']) ? $responseArray['statusCode'] : '';
        $this->responseMessage = isset($responseArray['message']) ? $responseArray['message'] : '';
        $this->responseInfo = isset($responseArray['info']) ? $responseArray['info'] : '';
    }

    public function getResponseStatusCode(){
        return $this->responseStatusCode;
    }

    public function getResponseMessage(){
        return $this->responseMessage;
    }
    
    public function getResponseInfo(){
        return $this->responseInfo;
    }
}