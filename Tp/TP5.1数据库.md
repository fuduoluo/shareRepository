#### TP5.1数据库增删改查

> ORM是在M层（模型）中用的一种技术（工具，既然是工具它就有名字，它的名字就是Db类）
>
> orm即可以在C层直接使用，也可以在M层直接使用
>
> orm：对象关系模型映射，它把数据库中的每一张表映射成对象了。然后要操作这个表的时候，就用对象调方法（如->select()），用面向对象的形式就可以了。这样就不用写原生sql语句了
>
> 原文链接：https://blog.csdn.net/qq_33862644/article/details/79797536

##### 查询数据

###### 单个数据查询

```php
/**前缀可以省略但是需要配置*/
Db::table('think_user')->where('id',1)->find();

//查询不存在并想返回异常,table方法必须指定完整的数据表名
Db::table('think_user')->where('id',1)->findOrFail();

//查询不存在想返回空数组 ,table方法必须指定完整的数据表名
Db::table('think_user')->where('id',1)->findOrEmpty();
```

> find 方法查询结果不存在，返回 null，否则返回结果数组

###### 多个数据[数据集]查询

```php
Db::table('think_user')->where('status',1)->select();
```

> select 方法查询结果是一个二维数组，如果结果不存在，返回空数组

###### 查询某个字段值

```php
// 返回某个字段的值
Db::table('think_user')->where('id',1)->value('name');
//不存在返回NULL
```

###### 查询某列的值

```php
// 返回数组
Db::table('think_user')->where('status',1)->column('name');
// 指定id字段的值作为索引
Db::table('think_user')->where('status',1)->column('name','id');

//返回完整数据并添加索引值的话
Db::table('think_user')->where('status',1)->column('*','id');

//不存在返回空数组
```

###### 分批查询处理[大数据记录查询]

> `chunk`方法的处理默认是根据主键查询
>
> `chunk`方法一般用于命令行操作批处理数据库的数据，不适合WEB访问处理大量数据，很容易导致超时。

```php
//分批处理用户数据
Db::table('think_user')->chunk(100, function($users) {
    foreach ($users as $user) {
        //进行处理
        // 处理结果集...
		if($user->status==0){
            return false;
            //终止查询
        }
    }
});
// 或者交给回调方法myUserIterator处理
Db::table('think_user')->chunk(100, 'myUserIterator');

//chunk前加入其他查询方法：where、order等
Db::table('think_user')
	->where('score','>',80)
	->chunk(100, function($users) {
   	 	foreach ($users as $user) {
        //
    	}
});

//查询指定字段:如create_time
Db::table('think_user')->chunk(100, function($users) {
    // 处理结果集...
    return false;
},'create_time');
//处理数据顺序：desc、asc
Db::table('think_user')->chunk(100, function($users) {
    // 处理结果集...
    return false;
},'create_time', 'desc');
```

###### 大数据处理：使用游标查询处理

```php
$cursor = Db::table('user')->where('status', 1)->cursor();
foreach($cursor as $user){
	echo $user['name'];
}
//cursor方法返回的是一个生成器对象，user变量是数据表的一条数据（数组）。
```

###### JSON数据类型查询

```php
// 查询JSON类型字段 （info字段为json类型）
Db::table('think_user')
	->where('info->email','thinkphp@qq.com')
	->find();
```

##### 新增数据

> 新增成功一般返回是1即true

###### 新增一条数据

```php
//不抛出异常
$data = ['foo' => 'bar', 'bar' => 'foo'];
Db::name('user')->strict(false)->insert($data);
//抛出异常
$data = ['foo' => 'bar', 'bar' => 'foo'];
Db::name('user')->data($data)->insert();
Db::name('user')->insert($data);
//返回新增主键Id
$userId = Db::name('user')->insertGetId($data);
```

###### 新增多条数据

```php
$data = [
    ['foo' => 'bar', 'bar' => 'foo'],
    ['foo' => 'bar1', 'bar' => 'foo1'],
    ['foo' => 'bar2', 'bar' => 'foo2']
];
Db::name('user')->insertAll($data);
//支持replace写入
Db::name('user')->insertAll($data,true);
Db::name('user')->data($data)->insertAll();
//限制插入数据条数
Db::name('user')->data($data)->limit(100)->insertAll();
```

##### 更新数据库数据

> update 方法返回影响数据的条数，没修改任何数据返回 0

```php
Db::name('user')->where('id', 1)->update(['name' => 'thinkphp']);

//使用data方法传入要更新的数据
Db::name('user')->where('id', 1)->data(['name' => 'thinkphp'])->update();

//数据中包含主键，可以直接使用
Db::name('user')->update(['name' => 'thinkphp','id'=>1]);
//支持sql数据更新
Db::name('user')
    ->where('id',1)
    ->inc('read_time')
    ->dec('score',3)
    ->exp('name','UPPER(name)')
    ->update();
/**v5.1.7+*/
Db::name('user')
    ->where('id', 1)
    ->update([
        'name'		=>	Db::raw('UPPER(name)'),
        'score'		=>	Db::raw('score-3'),
        'read_time'	=>	Db::raw('read_time+1')
    ]);
```

###### 更新单个字段值

> setField 方法返回影响数据的条数，没修改任何数据字段返回 0

```php
Db::name('user')
    ->where('id',1)
    ->setField('name', 'thinkphp');
```

> 自增自减

```php
// score 字段加 1
Db::table('think_user')
    ->where('id', 1)
    ->setInc('score');

// score 字段减 1
Db::table('think_user')
    ->where('id', 1)
    ->setDec('score');
```

> 延时更新数据:`setInc/setDec`支持延时更新
>
> 第三个参数是延时时间长度：10秒后进行更新

```php
Db::name('user')->where('id', 1)->setInc('score', 1, 10);
```

##### 删除数据

> delete 方法返回影响数据的条数，没有删除返回 0

###### 主键删除/条件删除

```php
// 根据主键删除
Db::table('think_user')->delete(1);
Db::table('think_user')->delete([1,2,3]);

// 条件删除    
Db::table('think_user')->where('id',1)->delete();
Db::table('think_user')->where('id','<',10)->delete();
```

###### 删除所有数据

```php
// 无条件删除所有数据
Db::name('user')->delete(true);
```

###### 软删除数据

```php
// 软删除数据 使用delete_time字段标记删除
Db::name('user')
	->where('id', 1)
	->useSoftDelete('delete_time',time())
    ->delete();

//生成sql语句如下：
UPDATE `think_user`  SET `delete_time` = '1515745214'  WHERE  `id` = 1
//useSoftDelete方法表示使用软删除，并且指定软删除字段为delete_time，写入数据为当前的时间戳。
```