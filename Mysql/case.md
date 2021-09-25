#### 1.指数形式生成百万数据
- 蠕虫复制命令以指数命令增长生成百万数据
```mysql
INSERT INTO hd_test (uname,age,city) SELECT uname,age,city FROM hd_test;
```