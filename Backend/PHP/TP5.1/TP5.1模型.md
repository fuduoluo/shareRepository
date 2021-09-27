### [typora完全使用详解](https://sspai.com/post/54912)

##### 新增字段

```mysql
[]表示可选
ALTER TABLE 表名 ADD COLUMN 字段名 字段类型(长度值) [NOT NULL] DEFAULT 默认值 comment '注释' ;

//例子
ALTER TABLE contract_templates ADD COLUMN contract_category_id int(11) NOT NULL DEFAULT 0 comment '范本分类ID' ;
```



### TP5.1模型类之增删改查

#### 新增

##### 添加一条数据

> 实例化传入的模型数据也不会经过修改器处理!!!!

```php
//1.实例化模型对象后赋值并保存
$user           = new User;
$user->name     = 'thinkphp';
$user->save();

//2.传入数据至save批量保存
$user->save([
    'name'  =>  'thinkphp',
    'email' =>  'thinkphp@qq.com'
]);

//3.直接在实例化的时候传入数据
$user = new User([
    'name'  =>  'thinkphp',
    'email' =>  'thinkphp@qq.com'
]);
$user->save();

//4.保存时过滤非数据表字段的数据

$user = new User;
// 过滤post数组中的非数据表字段数据
$user->allowField(true)->save($_POST);

//获取[自增ID]模型主键，根据数据库设置主键字段名称获取
echo $user->id;
echo $user->user_id;
```



##### 添加多条数据

```php
//批量新增
$user = new User;
$list = [
    ['name'=>'thinkphp','email'=>'thinkphp@qq.com'],
    ['name'=>'onethink','email'=>'onethink@qq.com']
];
$user->saveAll($list);


```

1. saveAll可以自动判断是更新还是新增操作
2. 存在主键-->更新，反之为新增

##### 静态方法

> create方法的第二个参数可以传入允许写入的字段列表（传入true则表示仅允许写入数据表定义的字段数据）

```php
$user = User::create([
    'name'  =>  'thinkphp',
    'email' =>  'thinkphp@qq.com'
], ['name', 'email']);
echo $user->name;
echo $user->email;
echo $user->id; // 获取自增ID
```



##### save、saveAll、create

```php
save方法新增数据:返回的是写入的记录数（通常是1），而不是自增主键值。
saveAll方法新增数据:返回的是包含新增模型（带自增ID）的数据集对象。
create方法:返回的是当前模型的对象实例。
```

##### 推荐做法：

> 使用create方法新增数据，使用saveAll批量新增数据。

#### 更新

> 如果需要使用模型事件，那么就先查询后更新，如果不需要使用事件，直接使用静态的`Update`方法进行条件更新，如非必要，尽量不要使用批量更新。

> - 实例化模型后调用`save`方法表示新增；
> - 查询数据后调用`save`方法表示更新；
> - `save`方法传入更新条件后表示更新；

##### 先查询后更新

```php
$user = User::get(1);
$user->name     = 'thinkphp';
$user->email    = 'thinkphp@qq.com';
$user->save();
```

###### 查询构造器使用

```php
//应对复杂情况
$user = User::where('status',1)
	->where('name','liuchen')
	->find();
$user->name     = 'thinkphp';
$user->email    = 'thinkphp@qq.com';
$user->save();
		
```

###### 强制更新

```php
$user = User::get(1);
$user->name     = 'thinkphp';
$user->force()->save();
```

###### 执行函数更新

```PHP
$user = User::get(1);
$user->score	=  Db::raw('score+1');
$user->save();
```

###### 字段的增加/减少

```php
$user = User::get(1);
$user->score	= ['inc', 1];//增加1
$user->score	= ['dec', 1];//减少1
$user->save();
```

##### 直接更新

> 最佳建议是在传入模型数据之前就进行过滤

```php
$user = new User();
// post数组中只有name和email字段会写入
$data = Request::only(['name','email']);
$user->save($data, ['id' => 1]);


// 过滤post数组中的非数据表字段数据
$user->allowField(true)->save($_POST,['id' => 1]);
// post数组中只有name和email字段会写入
$user>allowField(['name','email'])>save($_POST, ['id' => 1]);
```

##### 批量更新数据

```php
$user = new User;
$list = [
    ['id'=>1, 'name'=>'thinkphp', 'email'=>'thinkphp@qq.com'],
    ['id'=>2, 'name'=>'onethink', 'email'=>'onethink@qq.com']
];
$user->saveAll($list);
//返回是一个数据集
```

##### 静态方法

> 使用数据库方法更新

```php
User::where('id', 1)
    ->update(['name' => 'thinkphp']);
```



> 使用模型静态方法更新

```php
User::update(['id' => 1, 'name' => 'thinkphp']);
```

> isUpdate:控制显示指定当前调用是新增还是保存

```php
// 实例化模型
$user = new User;
// 显式指定更新数据操作
$user->isUpdate(true)
    ->save(['id' => 1, 'name' => 'thinkphp']);
// 显式指定当前操作为新增操作
$user->isUpdate(false)->save();
```

#### 删除

> 最佳实践：如果删除当前模型数据，用`delete`方法，如果需要直接删除数据，使用`destroy`静态方法。

##### 删除当前模型

```php+HTML
$user = User::get(1);
$user->delete();
```



##### 根据主键删除

```php
User::destroy(1);
// 支持批量删除多个数据
User::destroy('1,2,3');
// 或者
User::destroy([1,2,3]);
```

##### 条件删除

```php
//使用闭包删除
User::destroy(function($query){
    $query->where('id','>',10);
});
//数据库类查询删除
User::where('id','>',10)->delete();
```

#### 查询

##### 获取单个数据

```php
// 取出主键为1的数据
$user = User::get(1);
echo $user->name;

// 使用查询构造器查询满足条件的数据
$user = User::where('name', 'thinkphp')->find();
echo $user->name;
//5.1.5
新增：getOrFail() 当查询的数据不存在的时候会抛出ModelNotFound异常
//5.1.23
新增：findOrEmpty() 当查询数据不存在的话，返回空模型而不是Null
```

##### 获取多个数据

```php
// 根据主键获取多个数据
$list = User::all('1,2,3');
// 或者使用数组
$list = User::all([1,2,3]);
// 对数据集进行遍历操作
foreach($list as $key=>$user){
    echo $user->name;
}

//5.1.21+版本开始可以使用查询链式操作
// 使用查询构造器查询
$list = User::where('status', 1)->limit(3)->order('id', 'asc')->all();
foreach($list as $key=>$user){
    echo $user->name;
}
```

##### 使用查询构造器

> 使用查询构造器直接使用静态方法调用即可，无需先实例化模型。

```php
User::where('status',1)->order('id desc')->limit(10)->select();
```

###### 获取某个字段或者某个列的值

> `value`和`column`方法返回的不再是一个模型对象实例，而是纯粹的值或者某个列的数组

```php
// 获取某个用户的积分
User::where('id',10)->value('score');
// 获取某个列的所有值
User::where('status',1)->column('name');
// 以id为索引
User::where('status',1)->column('name','id');
```

###### 聚合查询[使用数据库聚合方法]

```php
User::count();
User::where('status','>',0)->count();
User::where('status',1)->avg('score');
```

###### 动态查询

```php
// 根据name字段查询用户
$user = User::getByName('thinkphp');

// 根据email字段查询用户
$user = User::getByEmail('thinkphp@qq.com');
```

###### 数据分批处理

> 处理大量数据

```php
User::chunk(100,function($users) {
    foreach($users as $user){
        // 处理user模型对象
    }
})
```

##### 最佳推荐：

> 在模型外部使用静态方法进行查询，内部使用动态方法查询，包括使用数据库的查询构造器。模型的查询始终返回对象实例，但可以和数组一样使用。