

<h2 align="center"><a href="https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_1">JSAPI支付</a></h2>

##### [名词解释](https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=2_2)

> 签名

商户后台和微信支付后台根据相同的密钥和算法生成一个结果，用于校验双方身份合法性。签名的算法由微信支付制定并公开，常用的签名方式有：MD5、SHA1、SHA256、HMAC等。

> JSAPI网页支付

JSAPI网页支付即前文说的公众号支付，可在微信公众号、朋友圈、聊天会话中点击页面链接，或者用微信“扫一扫”扫描页面地址二维码在微信中打开商户HTML5页面，在页面内下单完成支付。

> Openid

用户在公众号内的身份标识，不同公众号拥有不同的openid。商户后台系统通过登录授权、支付通知、查询订单等API可获取到用户的openid。主要用途是判断同一个用户，对用户发送客服消息、模版消息等。企业号用户需要使用[企业号userid转openid接口](http://qydev.weixin.qq.com/wiki/index.php?title=Userid与openid互换接口)将企业成员的userid转换成openid

[支付模式](https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=2_1)

JSAPI支付是用户在微信中打开商户的H5页面，商户在H5页面通过调用微信支付提供的JSAPI接口调起微信支付模块完成支付。应用场景有：

1. ◆ 用户在微信公众账号内进入商家公众号，打开某个主页面，完成支付
2. ◆ 用户的好友在朋友圈、聊天窗口等分享商家页面连接，用户点击链接打开商家页面，完成支付
3. ◆ 将商户页面转换成二维码，用户扫描二维码后在微信浏览器中打开页面后完成支付

[支付账户](https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=3_1)

| 		邮件中参数       | 	API参数名	    |  	详细说明 	 |
| ----------   | --------:   | :-------: |
| 	APPID   | 	appid     |   appid是微信公众账号或开放平台APP的唯一标识，在公众平台申请公众账号或者在开放平台申请APP账号后，微信会自动分配对应的appid，用于标识该应用。可在微信公众平台-->开发-->基本配置里面查看，商户的微信支付审核通过邮件中也会包含该字段值    |
| 微信支付商户号 | 	mch_id      |  商户申请微信支付后，由微信支付分配的商户收款账号。   |
| API密钥   | 	key      |  交易过程生成签名的密钥，仅保留在商户系统和微信支付后台，不会在网络中传播    |
|Appsecret |	secret	  |		AppSecret是APPID对应的接口密码，用于获取接口调用凭证access_token时使用。在微信支付中，先通过OAuth2.0接口获取用户openid，此openid用于微信内网页支付模式下单接口使用。	|

##### [接口规则](https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_1)

##### [开发步骤](https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_3)

- 设置支付目录

> 支付目录:商户最后请求拉起微信支付收银台的页面地址。
>
> 设置规则说明.查看文档
>
> 登录微信支付商户平台（pay.weixin.qq.com）-->产品中心-->开发配置，设置后一般5分钟内生效。

<img src="https://pay.weixin.qq.com/wiki/doc/api/img/chapter7_3_1.png" style="zoom:80%;" />

- 设置授权域名

> 开发JSAPI支付时，在统一下单接口中要求必传用户openid，而获取openid则需要您在公众平台设置获取openid的域名。

<img src="https://pay.weixin.qq.com/wiki/doc/api/img/chapter7_3_2.png"  />

<img src="https://pay.weixin.qq.com/wiki/doc/api/img/chapter7_3_3.png"  />

##### 业务时序图

![](https://pay.weixin.qq.com/wiki/doc/api/img/chapter7_4_1.png)

> 业务流程
>
> 微信客户端：手机
>
> 商户后台：后端
>
> 微信支付系统：微信支付官方后台

- 进入H5页面前，生成`图文消息链接`或者`二维码`
- 点击或是扫码后，支付用户使用微信浏览器[`微信客户端`]进入H5页面
- `商户后台`生成支付订单[写入数据库订单]
- `商户后台`生成商户订单[根据数据库订单生成商户订单]
- `商户后台`调用统一下单接口，请求`微信支付系统`，回调地址完成处理结束
- `微信支付系统`生成预支付订单信息
- 返回预付单信息到`商户后台`
- `商户后台`生成JSAPI页面调用的支付参数并签名返回`微信客户端`
- 返回支付参数【prepay_id,paySign等信息】
- `微信支付用户`发起JSAPI支付接口的`微信支付系统`请求。`微信支付系统`验证支付参数合法性和授权
- `微信支付系统`返回验证结果，要求`微信支付用户`输入支付密码
- `微信支付用户`确认支付，向`微信支付系统`提交支付授权并处理
- `微信支付系统`根据`商户后台`设置的回调地址异步通知商户支付结果，告知微信通知处理结果
- `微信支付系统`返回支付结果，并发微信消息提示到`微信客户端`展示支付结果
- `微信客户端接收到消息`后根据实际场景查询支付api接口返回等后续操作

##### 微信内H5调起支付[`微信客户端拉起微信支付`]

> 微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
>
> WeixinJSBridge内置对象在其他浏览器中无效

> `商户后台`根据`微信支付系统`返回的`预支付订单信息`生成`JSAPI页面调用的支付参数并签名`