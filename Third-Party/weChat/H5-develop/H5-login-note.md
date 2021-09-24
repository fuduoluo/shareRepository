#### 微信公众号登陆流程

> 前提：使用NATAPP内网穿透申请一个外网地址访问本地项目,后端已配置好以下常量值

```php
const APPID='wx3xxxxx4ac930';//测试账号APPID
const APPSECRET='576ffe25xxxxxx0c6fb882a9e0a';//测试秘钥
const REDIRECT_URI='http://t4dnxb.xxxx.cc/index/wxlogin/redirect_uri';//测试回调地址：尤其注意：跳转回调redirect_uri，应当使用https链接来确保授权code的安全性。
const SCOPE='snsapi_userinfo';//拥有授权作用域的权限
const STATE='TEST';
```

> 使用phpstudy集成环境测试80端口

> 框架：TP5.1  Vue

[登陆流程参考文章......点击这里](https://www.cnblogs.com/sunshq/p/5132811.html)

```php 
/***主要snsapi_userinfo发起的网页授权网页授权获取用户基本信息
第一步：用户同意授权，获取code
第二步：通过code换取网页授权access_token
第三步：刷新access_token（如果需要）
第四步：拉取用户信息(需scope为 snsapi_userinfo)
 */
```

##### 发请请求

> 前端：

```js
//es6
login(){
    this.$request.postData("index/wxlogin/getUserCode","",res=>{
        console.log(res);
        //复制返回地址到微信授权登录获取code
    }
   )
},
```

![image.png](https://i.loli.net/2020/03/27/R6aiBjp2TqmM5Eb.png)

##### 获取code

```php
$code = Request::param('code');
$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".
    self::APPID."&secret=".
        self::APPSECRET."&code=".
            $code."&grant_type=authorization_code";

$result = file_get_contents($url);
// 返回access_token和openid
$data = json_decode($result,true);        
if(isset($data['errcode']))
{
    return $this->returnJson(400, '链接失效');
}
return $this->returnJson(200, '成功', $data);
```

##### 拉取用户信息

```php
$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
$result = file_get_contents($url);
$data = json_decode($result,true);
dump($data);
// Cache::store('redis')->set($openid,$result);
return $data;
```

![image.png](https://i.loli.net/2020/03/27/ANdws5uD4mvHcUR.png)

> 具体请看wxlogindemo