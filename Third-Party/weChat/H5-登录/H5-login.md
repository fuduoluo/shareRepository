#### 关于网页[H5]微信授权登录

[文档....点这里](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html)

[demo案例](https://www.cnblogs.com/sunshq/p/5132811.html)

[沙盒地址](https://mp.weixin.qq.com/debug/cgi-bin/sandboxinfo?action=showinfo&t=sandbox/index)

###### 微信授权登录步骤

> APPID与APPSECRET已经获取到的情况下，进行以下步骤

> 本案例使用的scope是属于snsapi_userinfo发起的网页授权[用户需要手动确认发起请求]

![image-20200319153155147.png](https://i.loli.net/2020/03/20/c7Ww6E23ImtZJaX.png)

1. 进行微信授权，用户同意授权，返回获取code

   ```php
   $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".
     self::APPID."&redirect_uri=".self::REDIRECT_URI."&response_type=code&scope=".self::SCOPE."&state=".self::STATE."#wechat_redirect";
   ```

   > SCOPE:这里是使用snsapi_userinfo
   >
   > $state:自定义填写a-zA-Z0-9的参数值，最多128字节
   >
   > REDIRECT_URI：授权后重定向的回调链接地址

   > 同意授权后跳转至：xxxx/redirect_uri/?code=CODE&state=STATE

2. 获取access_token[基础支持中的access_token（该access_token用于调用其他接口）不同]

   ```php
   https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
   ```

   > code:填写第一步获取的code参数

   ```json
   //返回格式
   {
     "access_token":"ACCESS_TOKEN",
     "expires_in":7200,
     "refresh_token":"REFRESH_TOKEN",
     "openid":"OPENID",
     "scope":"SCOPE" 
   }
   ```

3. 刷新access_token忽略，请看文档

4. 拉取用户信息

   ```json
   GET（请使用https协议） https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
   ```

   ```json
   {   
     "openid":" OPENID",
     "nickname": NICKNAME,
     "sex":"1",
     "province":"PROVINCE",
     "city":"CITY",
     "country":"COUNTRY",
     "headimgurl":       "http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
     "privilege":[ "PRIVILEGE1" "PRIVILEGE2"     ],
     "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
   }
   ```
   
   ###### 实际使用：
   
   ```
   //获取到用户信息存入redis
   //参考直播项目
   $url=https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
   $result = file_get_contents($url);
   $data = json_decode($result,true);
   Cache::store('redis')->set($openid,$result);
   ```
   
   

