> `route`目录下的任何路由定义文件都是有效的，默认的路由定义文件是`route.php`，但你完全可以更改文件名，或者添加多个路由定义文件（你可以进行模块定义区分，但最终都会一起加载）

```php
├─route                 路由定义目录
│  ├─route.php          路由定义
│  ├─api.php            路由定义
│  └─...                更多路由定义
```

##### 路由定义

> ###### Route::快捷方法名('路由表达式','路由地址');
>
> 匹配到符合规则路由，后面定义路由就不会进行匹配

```php
Route::rule('new/:id','News/read','GET|POST');//定义GET和POST请求支持的路由规则
Route::get('new/:id','News/read'); // 定义GET请求路由规则
Route::post('new/:id','News/update'); // 定义POST请求路由规则
Route::put('new/:id','News/update'); // 定义PUT请求路由规则
Route::delete('new/:id','News/delete'); // 定义DELETE请求路由规则
Route::any('new/:id','News/read'); // 所有请求都支持的路由规则
```

##### 路由表达式

> 通常包含静态地址和动态地址，或者两种地址的结合
>
> `:`开头的参数都表示动态变量，并且会自动绑定到操作方法的对应参数
>
> 变量用`[ ]`包含起来后就表示该变量是路由匹配的可选变量,且只能放在最后面

```php
Route::rule('new/:year/:month/:day', 'News/read'); // 静态地址和动态地址结合
Route::rule(':user/:blog_id', 'Blog/read'); // 全动态地址
Route::rule('my', 'Member/myinfo'); // 静态地址路由
Route::get('blog/:year/[:month]','Blog/archive');
```

##### 完全匹配

> 路由表达式最后使用`$`符号

```php
Route::get('new/:cate$', 'News/category'); 
http://serverName/index.php/new/info    成功
http://serverName/index.php/new/info/1  失败

// 全局开启路由完全匹配
'route_complete_match'   => true,
```

##### 变量规则

```php
//全局自定义变量规则 app.php
'default_route_pattern'	=>	'[\w\-]+'
```

###### 局部变量规则

> 不需要开头添加`^`或者在最后添加`$`，也不支持模式修饰符，系统会自动添加

```php
// 定义GET请求路由规则 并设置name变量规则
Route::get('new/:name', 'News/read')
    ->pattern(['name' => '\w+']);
```

###### 全局变量规则

```php
// 设置name变量规则（采用正则定义）
Route::pattern('name', '\w+');
// 支持批量添加
Route::pattern([
    'name' => '\w+',
    'id'   => '\d+',
]
```

##### 路由地址

![image.png](https://upload-images.jianshu.io/upload_images/3098875-fe4777eebd23d440.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

[具体点击这里](https://www.kancloud.cn/manual/thinkphp5_1/353966)

##### 路由参数

[具体点击这里](https://www.kancloud.cn/manual/thinkphp5_1/353965)

![image.png](https://upload-images.jianshu.io/upload_images/3098875-084509934c9bf891.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)
![image.png](https://upload-images.jianshu.io/upload_images/3098875-be679d0cf1e937b5.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

##### 路由缓存

> 自动对每次的路由请求的调度进行缓存，第二次如果是相同的请求则无需再次经过路由解析，而是直接进行请求调度
>
> 某个路由规则的路由地址是闭包，则无法使用路由缓存功能

```php
//开启
'route_check_cache'	=>	true
//清除
php think clear --route 
```

##### 跨域请求

[具体点击这里](https://www.kancloud.cn/manual/thinkphp5_1/489844)

##### 路由分组

> ###### Route::group('分组名（字符串）或者分组路由参数（数组）','分组路由规则（数组或者闭包）');

```php
//使用闭包方式注册路由分组,路由规则设置一些公共的路由参数
Route::group(['method' => 'get', 'ext' => 'html'], function () {
    Route::rule('blog/:id', 'blog/read');
    Route::rule('blog/:name', 'blog/read');
})->pattern(['id' => '\d+', 'name' => '\w+']);
//简化路由定义
Route::group('blog', function () {
    Route::get(':id', 'blog/read');
    Route::post(':id', 'blog/update');
    Route::delete(':id', 'blog/delete');
})->ext('html')->pattern(['id' => '\d+']);

Route::group('blog', function () {
    Route::get(':id', 'read');
    Route::post(':id', 'update');
    Route::delete(':id', 'delete');
})->prefix('blog/')->ext('html')->pattern(['id' => '\d+']);
```

##### MISS路由

> 一旦设置了MISS路由，相当于开启了强制路由模式

```php
Route::miss('public/miss');
//分组
Route::group('blog', function () {
    Route::rule(':id', 'blog/read');
    Route::rule(':name', 'blog/read');
    Route::miss('blog/miss');
})->ext('html')
    ->pattern(['id' => '\d+', 'name' => '\w+']);
```

##### 快捷路由

```php
// 给User控制器设置快捷路由,想当是只要是在user控制器下的所有方法都是可以被调用的
Route::controller('user','index/User');
```

##### 路由生成

> ##### Url::build('地址表达式',['参数'],['URL后缀'],['域名'])

> ##### url('地址表达式',['参数'],['URL后缀'],['域名'])

> 第一个参数必须和路由定义的路由地址保持一致
>
> index/blog/read  和 url('index/blog/read)

1. ##### 使用模块/控制器/操作生成

```php
// 生成index模块 blog控制器的read操作 URL访问地址
Url::build('index/blog/read', 'id=5&name=thinkphp');
// 使用助手函数
url('index/blog/read', 'id=5&name=thinkphp');
```

```php
//生成地址：
/index.php/blog/5/name/thinkphp.html
```