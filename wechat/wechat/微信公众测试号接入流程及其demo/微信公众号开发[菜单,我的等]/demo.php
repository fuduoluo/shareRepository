```php
<?php

/** * wechat php test */
//define your token
//这个token需要和公众号接口配置信息中填的token一致
define("TOKEN", "weixintest");
$wechatObj = new wechatTest();
$wechatObj->valid();
class wechatTest
{
    //获取 echostr	随机字符串
    public function valid()
    {
        if (!isset($_GET["echostr"])) {
            $this->responseMsg();
        }
        //认证消息
        else if ($this->checkSignature()) {
            echo $_GET["echostr"];
            exit;
        } else {
            $this->responseMsg();
        }
    }
    // 验证TOKEN
    public function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    // 消息响应返回给用户
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            $GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents('php://input', 'r');
        }
        //extract post data
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection, the best way is to check the validity of xml by yourself */
            //libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE) {
                    /* 处理文本消息 */
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                    /* 处理事件消息 */
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            if ($resultStr != null) {
                $myfile = fopen("./1.txt", "w");
                fwrite($myfile, $resultStr);
                echo $resultStr;
            } else {
                echo "";
                exit();
            }
        }
    }
    // 进行格式转换xml
    private function receiveText($object)
    {
        $funcFlag = 1231232131231312;
        $contentStr = "发送的消息是" . $object->Content;
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        return $resultStr;
    }
    // 发送普通消息模板
    private function transmitText($object, $content, $funcFlag = 0)
    {

        $textTpl ="<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <MsgId>%d</MsgId>
      </xml>";
        $resultStr = sprintf(
            $textTpl,
            $object->FromUserName,
            $object->ToUserName,
            time(),
            $content,
            $funcFlag
        );
    
        return $resultStr;
    }
    private function receiveEvent($object)
    {
    }

}
```


