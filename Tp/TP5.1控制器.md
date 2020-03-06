

#### 控制器

dirname() 函数返回路径中的目录名称部分。

高内聚：复杂问题分成小问题作为每一小块，这个小块的功能已经不可分割了，已经足够简单

低耦合：关联的东西少 你写的东西比较通用 这里能用那里也能用不会出现 同样的功能 写在一个位置 其他位置都不能用的情况

```php
 echo dirname("c:/testweb/home.php") . "<br />";
// c:/testweb
```



##### 命名

###### 采用驼峰命名（首字母大写）

###### 典型控制器

```php
<?php
namespace app\index\controller;
use think\Controller;
//继承基类Controller
class Index extends Controller
{
    public function index()
    {
        return 'index';
    }
}
```

###### 访问

> http://efa.cn:9091/index/index/hello
>
> 访问url:域名/模块/控制器/方法

```php
/*http://efa.cn:9091/public/index/index/hello
省略访问public，配置public下的.htaccess进行重写
只针对Apache。Linux参照手册*/
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php?/$1 [QSA,PT,L]
</IfModule>

```

###### 命名空间

> TP5.1只能通过该方法
>
> 修改默认根命名空间app
> 通过在根目录上新建.env配置文件中设置

```php
 //源码加载如下：
 $this->namespace = $this->env->get('app_namespace', $this->namespace);
 $this->env->set('app_namespace', $this->namespace);
//.env文件内容如下
APP_NAMESPACE = application
    
<?php
namespace application\index\controller;

class Index 
{
    public function index()
    {
        return 'index';
    }
}
```

###### 前置操作

> beforeActionList

```php
protected $beforeActionList = [
    'first',
    'second' =>  ['except'=>'hello'],//表示这些方法不使用前置方法，
    'three'  =>  ['only'=>'hello,data'],//表示只有这些方法使用前置方法。
];
```

###### 跳转和重定向

```php
$this->success('新增成功', 'User/list');
$this->error('新增失败');
//重定向
$this->redirect('News/category', ['cate_id' => 2]);
$this->redirect('http://thinkphp.cn/blog/2',302);
//助手函数
//记住当前的URL后跳转
redirect('News/category')->remember();
//需要跳转到上次记住的URL的时候使用：
redirect()->restore();
```

###### 空操作和空控制器

```php
config/app.php下修改空控制器名
// 默认的空控制器名
'empty_controller'    => 'Error',
	
//空操作是指系统在找不到指定的操作方法的时候，会定位到空操作（`_empty`）方法来执行
```

> 空控制器

```php
<?php
namespace app\index\controller;

use think\Request;

class Error 
{
    public function index(Request $request)
    {
        //根据当前控制器名来判断要执行那个城市的操作
        $cityName = $request->controller();
        return $this->city($cityName);
    }
    //注意 city方法 本身是 protected 方法
    protected function city($name)
    {
        //和$name这个城市相关的处理
         return '当前城市' . $name;
    }
}
访问：http://serverName/index/beijing/ ：当前城市:beijing
```



> 空操作

```php
namespace app\index\controller;
class City 
{
    public function _empty($name)
    {
        //把所有城市的操作解析到city方法
        return $this->showCity($name);
    }
    
    //注意 showCity方法 本身是 protected 方法
    protected function showCity($name)
    {
        //和$name这个城市相关的处理
         return '当前城市' . $name;
    }
}
访问：http://serverName/index/city/beijing/：当前城市:beijing
```

###### 分层控制器

> 分层控制器是不能够被URL访问直接调用到的，只能在访问控制器、模型类的内部，或者视图模板文件中进行调用。

```php
<?php
namespace app\index\event;

class Blog 
{
    public function insert()
    {
        return 'insert';
    }
    
    public function update($id)
    {
        return 'update:'.$id;
    }
    
    public function delete($id)
    {
        return 'delete:'.$id;
    }
}
//实例化使用：
$event = controller('Blog', 'event');
echo $event->update(5); // 输出 update:5
echo $event->delete(5); // 输出 delete:5
//实例化跨模块使用：
$event = controller('Admin/Blog', 'event');
echo $event->update(5); // 输出 update:5
//直接调用分层控制器类的某个方法
echo action('Blog/update', ['id' => 5], 'event'); // 输出 update:5
//视图直接使用分层控制器
{:action('Blog/insert', '', 'event')}
{:action('Blog/delete', ['id' => '1'], 'event')}
//助手函数
{:widget('Blog/insert')}
{:widget('Blog/delete', ['id' => '123'])}
```

###### 中间件

[中间件解释](https://blog.csdn.net/qq_35081380/article/details/98466059)



