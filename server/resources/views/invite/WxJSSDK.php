<?php
define("SITE_PATH", "/home/wwwroot/saas/server/");
class WxJSSDK
{

    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++)
        {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket()
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $ticketfile = SITE_PATH . "/config/jsapi_ticket.json";
        $data = json_decode(file_get_contents($ticketfile));
        if ($data->expire_time < time())
        {
            $accessToken = $this->getAccessToken();
            $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket)
            {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen($ticketfile, "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else
        {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    private function getAccessToken()
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $access_token_file = SITE_PATH . "/config/access_token.json";
        $data = json_decode(file_get_contents($access_token_file));
        if ($data->expire_time < time())
        {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token)
            {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen($access_token_file, "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else
        {
            $access_token = $data->access_token;
        }
        return $access_token;
    }
    public function downloadVoice($mediaid)
    {
        $token = $this->getAccessToken();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$token&media_id=$mediaid";
        $ext = "amr";
        
        $content = $this->httpGet($url);
        $filename = "/uploads/ent/$mediaid".".".$ext;
        $rawfilename = SITE_PATH."/data/".$filename;
        file_put_contents($rawfilename, $content);
        
         $url = "http://p3.ucai.cn/data".$filename."?size=".  filesize($rawfilename);
        return $url;
    }
    public function downloadPicture($mediaid)
    {
        $token = $this->getAccessToken();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$token&media_id=$mediaid";
        $ext = "png";
        
        $content = $this->httpGet($url);
        $filename = "/uploads/ent/$mediaid"."_big.".$ext;
        $rawfilename = SITE_PATH."/data/".$filename;
        file_put_contents($rawfilename, $content);
        $smallfilename = "/uploads/ent/$mediaid".".".$ext;
        $rawsmallfilename = SITE_PATH."/data/".$smallfilename;
        
        $cmd = "convert $rawfilename -resize 200x200 $rawsmallfilename";
        system($cmd);
        $smallurl = "http://p3.ucai.cn/data".$smallfilename."?size=".  filesize($rawsmallfilename);
        $url = "http://p3.ucai.cn/data".$filename."?size=".  filesize($rawfilename);
        return array("url"=>$url, "smallurl"=>$smallurl);
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

}
