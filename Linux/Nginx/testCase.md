#### 1.计算请求url耗时时长
[参考文章](https://www.cgtblog.com/wx/3532.html)
```Nginx
// curl-format.txt内容如下
    time_namelookup:  %{time_namelookup}\n
       time_connect:  %{time_connect}\n
    time_appconnect:  %{time_appconnect}\n
   time_pretransfer:  %{time_pretransfer}\n
      time_redirect:  %{time_redirect}\n
 time_starttransfer:  %{time_starttransfer}\n
                    ----------\n
         time_total:  %{time_total}\n
// 执行语句		 
curl -w "@curl-format.txt" -o /dev/null -s "https://api.weixin.qq.com/sns/jscode2session?xxx=xxx&xx=aa"
```
##### 使用介绍
- 1、在某个目录下，新建一个文件，比如 curl-format.txt
- 2. 在同一个目录下，执行 curl 操作
##### 参数介绍：
- time_namelookup: DNS 域名解析的时候，就是把 https://zhihu.com 转换成 ip 地址的过程
- time_connect: TCP 连接建立的时间，就是三次握手的时间
- time_appconnect: SSL/SSH 等上层协议建立连接的时间，比如 connect/handshake 的时间
- time_pretransfer: 从开始到最后一个请求事务的时间
- time_redirect: 从请求开始到响应开始传输的时间
- time_starttransfer: 从请求开始到第一个字节将要传输的时间
- time_total: 这次请求花费的全部时间

##### [额外附加](https://www.ruanyifeng.com/blog/2019/09/curl-reference.html)
- curl 参数说明：-O 输出至文件  -s 忽略报错信息  

#### 2.Curl 发送GET/POST表单请求
```
curl  www.baidu.com 
//默认GET
curl -d '{"userid":"xx"}'  -H 'Content-type:application/json ' -H'x-tif-paasid:xxxx' http://xxx.test.com 
// Post请求设置Header头 body信息
```
##### 使用介绍
- 在xshell或者服务器终端中执行以上命令
##### 参数介绍
- -d ：发送表单参数内容可以自定义（默认使用Post请求）可忽略 "-X post"
- -H ：设置Header头,多个H 以逗号隔开
