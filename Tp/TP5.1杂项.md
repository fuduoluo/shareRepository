##### 缓存

> 引用 use think\facade\Cache

```php
配置目录下面的cache.php文件
内置支持的缓存类型包括file、memcache、wincache、sqlite、redis和xcache。
```

> 使用

```php
//设置
Cache::set('name',$value,3600);
// name自增（步进值为1）
Cache::inc('name');
// name自增（步进值为3）
Cache::inc('name',3);
// name自减（步进值为1）
Cache::dec('name');
// name自减（步进值为3）
Cache::dec('name',3);

//读取
dump(Cache::get('name')); 

//删除
Cache::rm('name'); 
//获取并删除
Cache::pull('name'); 

//清空
Cache::clear(); 
```

##### session

> 引用 use think\facade\Session

```php
// 赋值（当前作用域）
Session::set('name','thinkphp');
// 赋值think作用域
Session::set('name','thinkphp','think');

// 判断（当前作用域）是否赋值
Session::has('name');
// 判断think作用域下面是否赋值
Session::has('name','think');

// 取值（当前作用域）
Session::get('name');
// 取值think作用域
Session::get('name','think');

// 删除（当前作用域）
Session::delete('name');
// 删除think作用域下面的值
Session::delete('name','think');
```

##### cookie

> 引用 use think\facade\Cookie

```php
// 设置Cookie 有效期为 3600秒
Cookie::set('name','value',3600);
// 设置cookie 前缀为think_
Cookie::set('name','value',['prefix'=>'think_','expire'=>3600]);
// 支持数组
Cookie::set('name',[1,2,3]);

Cookie::has('name');
// 判断指定前缀的cookie值是否存在
Cookie::has('name','think_');

Cookie::get('name');
// 获取指定前缀的cookie值
Cookie::get('name','think_');

//删除cookie
Cookie::delete('name');
// 删除指定前缀的cookie
Cookie::delete('name','think_');

// 清空指定前缀的cookie
Cookie::clear('think_');
```

##### 多语言

[具体看这里](https://www.kancloud.cn/manual/thinkphp5_1/354119)

##### 分页 paginate

> DB类 

```php
// 查询状态为1的用户数据 并且每页显示10条数据
$list = Db::name('user')->where('status',1)->paginate(10);
// 把分页数据赋值给模板变量list
$this->assign('list', $list);
```

> 模型

```php
// 查询状态为1的用户数据 并且每页显示10条数据
$list = User::where('status',1)->paginate(10);
// 把分页数据赋值给模板变量list
$this->assign('list', $list);
```

> 视图输出

```
{$list|raw}
```

> 分页后数据处理

```php
$list = User::where('status',1)->paginate()->each(function($item, $key){
    $item->nickname = 'think';
});

//Db类操作分页数据的话，each方法的闭包函数中需要使用返回值
$list = Db::name('user')->where('status',1)->paginate()->each(function($item, $key){
    $item['nickname'] = 'think';
    return $item;
});
```

##### 上传

[具体看这](https://www.kancloud.cn/manual/thinkphp5_1/354121)