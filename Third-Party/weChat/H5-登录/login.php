<?php
// 主要snsapi_userinfo发起的网页授权
// 网页授权获取用户基本信息
/***
 * 第一步：用户同意授权，获取code

　　第二步：通过code换取网页授权access_token

　　第三步：刷新access_token（如果需要）

　　第四步：拉取用户信息(需scope为 snsapi_userinfo)
 */
namespace app\index\controller;
use think\facade\Request;
class Wxlogin{
    const APPID='wx3f2xxxxxxxc930';//测试账号APPID
    const APPSECRET='576ffxxxxxxxxxa9e0a';//测试秘钥
    const REDIRECT_URI='http://t4dnxb.xxxxx.cc/index/wxlogin/redirect_uri';//测试回调地址：尤其注意：跳转回调redirect_uri，应当使用https链接来确保授权code的安全性。
    const SCOPE='snsapi_userinfo';//拥有授权作用域的权限
    const STATE='TEST';
   
    public function getUserCode(){
        
        // 获取code请求地址
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".
        self::APPID."&redirect_uri=".
        self::REDIRECT_URI."&response_type=code&scope=".
        self::SCOPE."&state=".
        self::STATE."#wechat_redirect";
        return $this->returnJson(200, '成功', ['url'=>$url]);
    }
    //回调接收 code 并获取 access_token
    //成功返回用户token
    public function redirect_uri()
    {
        
        $code = Request::param('code');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".
            self::APPID."&secret=".
            self::APPSECRET."&code=".
            $code."&grant_type=authorization_code";

        $result = file_get_contents($url);
        // 返回access_token和openid
        // 返回格式如下：
        /***
         * {
            "access_token":"ACCESS_TOKEN",
            "expires_in":7200,
            "refresh_token":"REFRESH_TOKEN",
            "openid":"OPENID",
            "scope":"SCOPE" 
            }
         */
        $data = json_decode($result,true);        
        if(isset($data['errcode']))
        {
            return $this->returnJson(400, '链接失效');
        }
        $this->getUserInfo($data['access_token'],$data['openid']);
        // //获取openid
        // $userWxInfo = $this->is_have_openid($data['access_token'],$data['openid']);
        // //做你想做的事
        // $token = md5(time() . $userWxInfo['openid'] . 'TEST');
        // $data = [
        //     'token' => $token
        // ];
        // Cache::store('redis')->set($token, $userWxInfo, 3600 * 24 * 7);
        // return $this->returnJson(200, '成功', $data);

    }
    // 获取用户信息
    public function getUserInfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $result = file_get_contents($url);
        $data = json_decode($result,true);
        dump($data);
        // Cache::store('redis')->set($openid,$result);
        return $data;
    }
    /**
     * 返回数据
     * @param $code
     * @param $msg
     * @param array $data
     * @return string
     */
    public function returnJson($code, $msg, $data = [])
    {
        return json_encode(['code'=>$code, 'msg'=>$msg, 'data'=>$data]);
    }
}