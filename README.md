# yii2-sendcloud

need configuration

'components' => [
        ....
        'sendmailer' => [
            'class' => 'xiejianxuan5\sendcloud\Mailer',
            'apiUser' => "",
            'apiKey' => "",
            'from' => "",
            'fromName' => "",
        ],
        ....
    ],


usage

	* $to = "xxx@163.com";
	* $subject = "整合测试";
	* $html = "";
	* $template_name = "test_template_active";
	* $template_vars = ["%param1%" => ["zhangsan"],"%param2%"=>["16"],"%param3%"=>["man"]];

    $mailer = Yii::$app->sendmailer;
    $result = $mailer->sender($to,$subject,$html,$template_name, $template_vars);
    if(!$result){
    	var_dump($mailer->getResponseStatusCode(), $mailer->getResponseMessage(), $mailer->getResponseInfo());
    } else {
    	var_dump($mailer->getEmailIdList());
    }