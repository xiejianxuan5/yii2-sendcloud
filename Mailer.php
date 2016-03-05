<?php
namespace xiejianxuan5\sendcloud;
/**
 * SendCloud（https://sendcloud.sohu.com）
 */

class Mailer extends SendCloud
{
    public $module = 'mail';
    // 发件人地址. 举例: support@ifaxin.com, 爱发信支持<support@ifaxin.com>
    public $from;
    // 发件人名称. 显示如: ifaxin客服支持<support@ifaxin.com>
    public $fromName;
    // 收件人地址. 多个地址使用';'分隔, 如 ben@ifaxin.com;joe@ifaxin.com
    public $to = array();
    // 标题. 不能为空
    public $subject;
    // 邮件的内容. 邮件格式为text/html
    public $html;
    // 邮件的内容. 邮件格式为 text/plain
    public $plain;
    // 抄送地址. 多个地址使用';'分隔
    public $cc = array();
    // 密送地址. 多个地址使用';'分隔
    public $bcc = array();
    // 设置用户默认的回复邮件地址. 如果 replyTo 没有或者为空, 则默认的回复邮件地址为 from
    public $replyTo;
    // 本次发送所使用的标签ID. 此标签需要事先创建
    public $labelId;
    // 邮件头部信息. JSON 格式, 比如:{"header1": "value1", "header2": "value2"}
    public $headers = array();
    // 邮件附件. 发送附件时, 必须使用 multipart/form-data 进行 post 提交 (表单提交)
    public $attachments = array();
    // 默认值: true. 是否返回 emailId. 有多个收件人时, 会返回 emailId 的列表
    public $respEmailId = true;
    // 默认值: false. 是否使用回执
    public $useNotification = false;
    // 默认值: false. 是否使用地址列表发送. 比如: to=group1@maillist.sendcloud.org;group2@maillist.sendcloud.org
    public $useAddressList = false;
    public $isUseTemplate = false;
    // 邮件模板调用名称
    public $templateInvokeName;
    // 邮件模板中的参数, 如果多个收件人, 则与收件人一一对应
    public $templateVars = array();
    public function setFrom($from){
        $this->from = (string) $from;
    }
    public function setFromName($fromName){
        $this->fromName = (string) $fromName;
    }
    public function setTo($to){
        if(!is_array($to)){
            $to = array($to);
        }
        $this->to = $to;
        return $this;
    }
    public function setSubject($subject){
        $this->subject = (string) $subject;
        return $this;
    }
    public function setHtml($html){
        $this->html = (string) $html;
        return $this;
    }
    public function setPlain($plain){
        $this->plain = (string) $plain;
        return $this;
    }
    public function setCc($cc){
        if( !is_array($cc) ){
            $cc = array($cc);
        }
        $this->cc = $cc;
        return $this;
    }
    public function setBcc($bcc){
        if( !is_array($bcc) ){
            $bcc = array($bcc);
        }
        $this->bcc = $bcc;
        return $this;
    }
    public function setReplyTo($replyTo){
        $this->replyTo = (string) $replyTo;
        return $this;
    }
    public function setLabelId($labelId){
        $this->labelId = intval($labelId);
        return $this;
    }
    public function setHeaders($headers){
        if( !is_array($headers) ){
            $headers = array($headers);
        }
        $this->headers = $headers;
        return $this;
    }
    public function setAttachments($attachments){
        if( !is_array($attachments) ){
            $attachments = array($attachments);
        }
        $this->attachments = $attachments;
        return $this;
    }
    public function setRespEmailId($respEmailId){
        $this->respEmailId = (boolean) $respEmailId;
    }
    public function setUseNotification($useNotification){
        $this->useNotification = (boolean) $useNotification;
        return $this;
    }
    public function setUseAddressList($useAddressList){
        $this->useAddressList = (boolean) $useAddressList;
        return $this;
    }
    public function setTemplateInvokeName($templateInvokeName){
        $this->isUseTemplate = true;
        $this->templateInvokeName = (string) $templateInvokeName;
        return $this;
    }
    public function setTemplateVars($templateVars){
        $this->templateVars = $templateVars;
        return $this;
    }

    /**
     * $to = "xxx@163.com";
     * $subject = "整合测试";
     * $template_name = "test_template_active";
     * $template_vars = ["%param1%" => ["zhangsan"],"%param2%"=>["16"],"%param3%"=>["man"]];     
     * 
     * @param  string $to 发送人
     * @param  string $subject 标题
     * @param  string $html 邮件内容（如果template_name 有值，该参数失效）
     * @param  string $template_name 模板名称（sendCloud 后台上传的模板名称）
     * @param  array $template_vars 模板中的变量参数对应的值
     * @return [type]
     */
    public function sender($to, $subject, $html="", $template_name="", $template_vars=[])
    {
        $sender = $this->setTo($to)
                    ->setSubject($subject)
                    ->setHtml($html);
                    if (!empty($template_name)) {
                        $sender->setTemplateInvokeName($template_name)
                               ->setTemplateVars($template_vars);
                    }
        return $sender->send();
        // if (!$result) {
        //     $fail_log = new SendMailFailLog;
        //     $fail_log->email = $to;
        //     $fail_log->template = $template_name;
        //     $fail_log->code = $sender->getResponseStatusCode();
        //     $fail_log->message = $sender->getResponseMessage();
        //     $fail_log->save();
        //     return false;
        // } else {
        //     $success_log = new SendMailSuccessLog;
        //     $success_log->email = $to;
        //     $success_log->template = $template_name;
        //     $success_log->save();
        //     return true;
        // }

    }

    /**
     * 发送邮件
     */
    public function send(){
        $action = $this->isUseTemplate ? 'sendtemplate' : 'send';
        $parameters['apiUser'] = $this->apiUser;
        $parameters['apiKey']  = $this->apiKey;
        if($this->from) $parameters['from'] = $this->from;
        if($this->fromName) $parameters['fromName'] = $this->fromName;
        if( !empty($this->to) ) $parameters['to'] = implode(';', $this->to);
        if($this->subject) $parameters['subject'] = $this->subject;
        if($this->html) $parameters['html'] = $this->html;
        if($this->plain) $parameters['plain'] = $this->plain;
        if( !empty($this->cc) ) $parameters['cc'] = implode(';', $this->cc);
        if( !empty($this->bcc) ) $parameters['bcc'] = implode(';', $this->bcc);
        if($this->replyTo) $parameters['replyTo'] = $this->replyTo;
        if($this->labelId) $parameters['labelId'] = $this->labelId;
        if( !empty($this->headers) ) $parameters['headers'] = json_encode($this->headers);
        if($this->respEmailId){
            $parameters['respEmailId'] = 'true';
        }else{
            $parameters['respEmailId'] = 'false';
        }
        if($this->useNotification){
            $parameters['useNotification'] = 'true';
        }
        if($this->useAddressList){
            $parameters['useAddressList'] = 'true';
        }
        // 附件
        if( !empty($this->attachments) ){
            $attachments = array();
            foreach ($this->attachments as $key => $attachment) {
                $parameters['attachments['.$key.']'] = '@' . $attachment;
            }
        }
        // 模板发送
        if($this->templateInvokeName) $parameters['templateInvokeName'] = $this->templateInvokeName;
        // 设置xsmtpapi信息
        if($this->isUseTemplate){
            // $parameters['xsmtpapi']['to'] = $this->to;
            $parameters['xsmtpapi']['to'] = isset($parameters['to']) ? explode(";", $parameters['to']) : $this->to;
            if( !empty($this->templateVars) ) $parameters['xsmtpapi']['sub'] = $this->templateVars;
        }
        if( isset($parameters['xsmtpapi']) ){
            $parameters['xsmtpapi'] = json_encode($parameters['xsmtpapi']);
        }
        return $this->sendRequest($this->module, $action, $parameters);
    }
    public function getEmailIdList(){
        $responseInfo = $this->getResponseInfo();
        return isset($responseInfo['emailIdList']) ? $responseInfo['emailIdList'] : array();
    }
}