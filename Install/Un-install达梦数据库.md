#### 达梦数据库安装教程
- [请参考文章](https://blog.csdn.net/weixin_42082084/article/details/115450532?utm_medium=distribute.pc_relevant.none-task-blog-2~default~baidujs_title~default-1.no_search_link&spm=1001.2101.3001.4242)
##### 达梦数据库初始化设置
```
//初始化密码数据库名等信息
./dminit path=/dm8/data SYSDBA_PWD=123456789 PAGE_SIZE=32 LOG_SIZE=2048 CASE_SENSITIVE=Y CHARSET=1 PORT_NUM=5236 DB_NAME=SYSDBA INSTANCE_NAME=DMSERVER EXTENT_SIZE=16 BLANK_PAD_MODE=0 LENGTH_IN_CHAR=1
```
##### 达梦数据库错误排查参考
- [请参考文章](https://www.pianshen.com/article/84491270809/)