#### 常用MySQL点

1.连接数据库

```mysql
/* 连接与断开服务器 */
mysql -h 地址 -P 端口 -u 用户名 -p 密码

/*显示正在运行线程*/
show processlist
```



2. 数据库增删改查

```mysql
/* 数据操作 */ ------------------
-- 增
    INSERT [INTO] 表名 [(字段列表)] VALUES (值列表)[, (值列表), ...]
        -- 如果要插入的值列表包含所有字段并且顺序一致，则可以省略字段列表。
        -- 可同时插入多条数据记录！
        REPLACE 与 INSERT 完全一样，可互换。
    INSERT [INTO] 表名 SET 字段名=值[, 字段名=值, ...]
-- 查
    SELECT 字段列表 FROM 表名[ 其他子句]
        -- 可来自多个表的多个字段
        -- 其他子句可以不使用
        -- 字段列表可以用*代替，表示所有字段
-- 删
    DELETE FROM 表名[ 删除条件子句]
        没有条件子句，则会删除全部
-- 改
    UPDATE 表名 SET 字段名=新值[, 字段名=新值] [更新条件]
    
-- 新增字段[修改字段类型注释长度]
[]表示可选
ALTER TABLE 表名 ADD COLUMN 字段名 字段类型(长度值) [NOT NULL] DEFAULT 默认值 comment '注释' ;

//例子
ALTER TABLE contract_templates ADD COLUMN contract_category_id int(11) NOT NULL DEFAULT 0 comment '范本分类ID' ;
//删除指定数据
DELETE FROM 表名 WHERE 列名=值；
//修改字段内容或者注释
ALTER TABLE  efa_order modify column pay_type tinyint(2) NOT NULL COMMENT '购买方式1:微信,2:线下1';

//根据条件转换时间戳为日期格式
UPDATE efa_credits_log
SET create_time = CASE
WHEN create_time = '' THEN
	''
WHEN create_time = 0 THEN
	''
WHEN create_time = 1 THEN
	''
ELSE
	FROM_UNIXTIME(create_time)
END;
```

3.数据类型

1. 数值类型

```mysql
   类型         字节     范围（有符号位）
    tinyint     1字节    -128 ~ 127      无符号位：0 ~ 255
    smallint    2字节    -32768 ~ 32767 
    mediumint   3字节    -8388608 ~ 8388607
    int         4字节    -2147483648 ~ 2147483647
    bigint      8字节    -9233372036854775808 ~ 9223372036854775807
```

```mysql
/* 注意 */
int(3/11):3或者11代表的是显示字符的长度，允许的最大值是INT整数所允许的最大值
长度不足是会自动帮你补全
例如：int(3):输入10 =>补全010
/*mysql无符号和有符号的区别*/
无符号unsigned 表示设置的的数据为0或者正数；
有符号则可以是负数 -；
内存占比 有符号 0-255 无符号 -127~127
```
1. 字符串类型

   ```mysql
    char    定长字符串，速度快，但浪费空间
    varchar 变长字符串，速度慢，但节省空间
    M表示能存储的最大长度，此长度是字符数，非字节数。
    
    char,最多255个字符，与编码无关。
    varchar,最多65535字符，与编码有关。
    
    一条有效记录最大不能超过65535个字节。
    utf8 最大为21844个字符，gbk 最大为32766个字符，latin1 最大为65532个字符
    varchar 是变长的，需要利用存储空间保存 varchar 的长度，如果数据小于255个字节，则采用一个字节来保存长度，反之需要两个字节来保存。
    
    varchar 的最大有效长度由最大行大小和使用的字符集确定
   ```

   

2. 日期类型

   ```mysql
   datetime    8字节   日期及时间  1000-01-01 00:00:00 到 9999-12-31 23:59:59
   date        3字节   日期         1000-01-01 到 9999-12-31
   timestamp   4字节   时间戳        19700101000000 到 2038-01-19 03:14:07
   time        3字节   时间         -838:59:59 到 838:59:59
   year        1字节   年份         1901 - 2155
   ```

   支持时间格式

   ```mysql
   datetime    YYYY-MM-DD hh:mm:ss
   timestamp   YY-MM-DD hh:mm:ss
               YYYYMMDDhhmmss
               YYMMDDhhmmss
               YYYYMMDDhhmmss
               YYMMDDhhmmss
   date        YYYY-MM-DD
               YY-MM-DD
               YYYYMMDD
               YYMMDD
               YYYYMMDD
               YYMMDD
   time        hh:mm:ss
               hhmmss
               hhmmss
   year        YYYY
               YY
               YYYY
               YY
   ```

   

3. 枚举和集合类型

   ```mysql
   enum(val1, val2, val3...)
       在已知的值中进行单选。最大数量为65535.
       枚举值在保存时，以2个字节的整型(smallint)保存。每个枚举值，按保存的位置顺序，从1开始逐一递增。
       表现为字符串类型，存储却是整型。
       NULL值的索引是NULL。
       空字符串错误值的索引值是0。
   
   -- 集合（set） ----------
   set(val1, val2, val3...)
       create table tab ( gender set('男', '女', '无') );
       insert into tab values ('男, 女');
       最多可以有64个不同的成员。以bigint存储，共8个字节。采取位运算的形式。
       当创建表时，SET成员值的尾部空格将自动被删除。
   ```

   ##### 选用角度

   ```php
   -- PHP角度
   1. 功能满足
   2. 存储空间尽量小，处理效率更高
   3. 考虑兼容问题
   ```

   ##### IP存储

   ```mysql
   1. 只需存储，可用字符串
   2. 如果需计算，查找等，可存储为4个字节的无符号int，即unsigned
       1) PHP函数转换
           ip2long可转换为整型，但会出现携带符号问题。需格式化为无符号的整型。
           利用sprintf函数格式化字符串
           sprintf("%u", ip2long('192.168.3.134'));
           然后用long2ip将整型转回IP字符串
       2) MySQL函数转换(无符号整型，UNSIGNED)
           INET_ATON('127.0.0.1') 将IP转为整型
           INET_NTOA(2130706433) 将整型转为IP
   ```

   ##### 主从表区分

   ```mysql 
   FOREIGN KEY 外键约束
   -- 用于限制主表与从表数据完整性。
   alter table t1 add constraint `t1_t2_fk` foreign key (t1_id) references t2(id);
   
   -- 将表t1的t1_id外键关联到表t2的id字段。
   -- 每个外键都有一个名字，可以通过 constraint 指定
   
   -- 存在外键的表，称之为从表（子表），外键指向的表，称之为主表（父表）
   
   -- 注意，外键只被InnoDB存储引擎所支持。其他引擎是不支持的。
   ```

   ##### 三大范式

   ```mysql
   -- Normal Format, NF
       - 每个表保存一个实体信息
       - 每个具有一个ID字段作为主键
       - ID主键 + 原子表
   -- 1NF, 第一范式
   	字段不能再分，就满足第一范式。
   -- 2NF, 第二范式
       满足第一范式的前提下，不能出现部分依赖。
       消除符合主键就可以避免部分依赖。增加单列关键字。
   -- 3NF, 第三范式
       满足第二范式的前提下，不能出现传递依赖。
       某个字段依赖于主键，而有其他字段依赖于该字段。这就是传递依赖。
       将一个实体信息的数据放在一个表内实现。
   ```

   

