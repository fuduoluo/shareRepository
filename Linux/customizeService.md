 #### CentOS7将Nginx添加系统服务的方法步骤
 
#### 导语
宝塔安装完，Nginx 已经运行正常，但是此时 [Nginx](https://blog.csdn.net/weixin_39811166/article/details/111724603) 并没有添加进系统服务。接下来会将 Nginx 添加进系统服务并且设置开机启动
#### 检查是否安装nginx
```angular2html
<!--查看端口-->
[root@localhost init.d]# netstat -tnlp|grep nginx
```
####查看nginx安装位置和配置文件位置
```angular2html
<!--查看nginx安装位置-->
[root@localhost init.d]# ps -ef | grep nginx
root      2998     1  0 11:37 ?        00:00:00 nginx: master process /www/server/nginx/sbin/nginx -c /www/server/nginx/conf/nginx.conf
<!--查看配置文件位置-->
[root@localhost init.d]# nginx -t
nginx: the configuration file /www/server/nginx/conf/nginx.conf syntax is ok
nginx: configuration file /www/server/nginx/conf/nginx.conf test is successful


```
#### 查看服务
![](https://img.jbzj.com/file_images/article/201903/2019030109371612.png)
![](https://img.jbzj.com/file_images/article/201903/2019030109371713.png)
#### 添加服务
在 /usr/lib/systemd/system 目录中添加 nginx.service，根据实际情况进行修改，详细解析可查看下方参考资料中的文章

[以下配置参数介绍](https://www.jb51.net/article/157144.htm)
```angular2html
[Unit]
Description=nginx - high performance web server
Documentation=http://nginx.org/en/docs/
After=network.target remote-fs.target nss-lookup.target
 
[Service]
Type=forking
##默认编译安装路径
#PIDFile=/usr/local/nginx/logs/nginx.pid
#ExecStartPre=/usr/local/nginx/sbin/nginx -t -c /usr/local/nginx/conf/nginx.conf
#ExecStart=/usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf

##修改为宝塔安装路径
PIDFile=/www/server/nginx/logs/nginx.pid
##            nginx安装目录           -c        nginx.conf配置文件目录
ExecStartPre=/usr/local/nginx/sbin/nginx -t -c /www/server/nginx/conf/nginx.conf
ExecStart=/usr/local/nginx/sbin/nginx -c /www/server/nginx/conf/nginx.conf

ExecReload=/bin/kill -s HUP $MAINPID
ExecStop=/bin/kill -s QUIT $MAINPID
PrivateTmp=true
 
[Install]
WantedBy=multi-user.target
```
#### 查看Nginx服务
```angular2html
systemctl status nginx
```
![](https://img.jbzj.com/file_images/article/201903/2019030109371713.png)
#### 启动Nginx服务
```angular2html
systemctl start nginx
```
![](https://img.jbzj.com/file_images/article/201903/2019030109371715.png)
#### 设置开机自启动
```angular2html
systemctl enable nginx
```
![](https://img.jbzj.com/file_images/article/201903/2019030109371716.png)
#### 查看是否配置成功
![](https://img.jbzj.com/file_images/article/201903/2019030109371817.png)