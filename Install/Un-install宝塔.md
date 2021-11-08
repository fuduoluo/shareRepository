#### 卸载Centos宝塔
- 1-使用xshell链接服务  进入服务器
-命令：wget http://download.bt.cn/install/bt-uninstall.2-    
- 执行脚本命令：sh bt-uninstall.sh
- 根据提示1-部分 2-全部卸载

#### 查看宝塔面板账号密码
```
[root@localhost /]# bt default
==================================================================
BT-Panel default info!
==================================================================
外网面板地址: http://xxxxx:8888/140f02fd
内网面板地址: http://xxxxx:8888/140f02fd
*以下仅为初始默认账户密码，若无法登录请执行bt命令重置账户/密码登录
username: xxxxxxxx
password: xxxxxxxx
If you cannot access the panel,
release the following panel port [8888] in the security group
若无法访问面板，请检查防火墙/安全组是否有放行面板[8888]端口
==================================================================
```