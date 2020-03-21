#### xmapp：phpmyadmin连接，管理多个mysql服务器[本地和内网]

> **方法一，修改phpMyAdmin/libraries/config.default.php**
>
> **修改配置文件前，最好先备份一下，万一改错地方了**

> **方法二，同时管理多个mysql服务器。**
>
> **1，将phpMyAdmin根目录下的config.sample.inc.php，重命名为config.inc.php**，将原本都注释掉
>
> **2，修改config.inc.php文件**，通过循环遍历连接

```js
$connect_hosts = array(
    '1' => array(
        "host"   => "localhost",  //服务器1  
        "user"   => "root",
        "password" => "root"
    ),
    '2' => array(
        "host"   => "IP地址", //服务器2  
        "user"   => "xxxx",
        "password" => "xxxx"
    )
);

for ($i = 1; $i <= count($connect_hosts); $i++) {
    /* Authentication type */
    $cfg['Servers'][$i]['auth_type'] = 'cookie';
    /* Server parameters */
    $cfg['Servers'][$i]['host'] = $connect_hosts[$i]['host'];   //修改host  
    $cfg['Servers'][$i]['connect_type'] = 'tcp';
    $cfg['Servers'][$i]['compress'] = false;
    /* Select mysqli if your server has it */
    $cfg['Servers'][$i]['extension'] = 'mysql';
    $cfg['Servers'][$i]['AllowNoPassword'] = true;//是否允许空密码登录
    $cfg['Servers'][$i]['user'] = $connect_hosts[$i]['user'];  //修改用户名  
    $cfg['Servers'][$i]['password'] = $connect_hosts[$i]['password']; //密码  
    /* rajk - for blobstreaming */
    $cfg['Servers'][$i]['bs_garbage_threshold'] = 50;
    $cfg['Servers'][$i]['bs_repository_threshold'] = '32M';
    $cfg['Servers'][$i]['bs_temp_blob_timeout'] = 600;
    $cfg['Servers'][$i]['bs_temp_log_threshold'] = '32M';
    /* User for advanced features */
    $cfg['Servers'][$i]['controluser'] = $connect_hosts[$i]['user'];
    $cfg['Servers'][$i]['controlpass'] = $connect_hosts[$i]['password'];
}
```

![image.png](https://i.loli.net/2020/03/21/8fBUC2chPSET3Jp.png)

##### 可能遇到问题：

> xmapp默认mysql密码是空的，所以导致PHPmyadmin登录不了，不允许空密码登录。

```js
$cfg['Servers'][$i]['AllowNoPassword'] = true;是否允许空密码登录
$cfg[‘blowfish_secret’] = ‘123456’; // 你要设置的密码
```

![image.png](https://upload-images.jianshu.io/upload_images/3098875-6038d7cbe84b56ed.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

#### xmapp和phpstudy同时启动mysql服务

![image.png](https://i.loli.net/2020/03/21/wGxWpndDcMNLoil.png)

> 之前会遇到两个mysql不能同时启动，现在比较简单做法是先确定xmapp的mysql版本号
>
> phpstudy相对于下载对应版本[端口号什么都不用改变，只需要安装对应版本就行]
>
> 我这phpstudy安装是5.5.29

![image.png](https://i.loli.net/2020/03/21/m19of5zyYnsIZMg.png)

