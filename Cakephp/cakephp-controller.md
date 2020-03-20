[cakePHP中文文档](http://docs.30c.org/cakephp/)

##### app控制器

> `AppController` 控制器是应用程序中所有控制器的父类

```php
控制器使用的组件(*components*)和助件(*helpers*)列表被特别处理。对这两个列表， `AppController` 中的值会和控制器子类中的(同名)数组合并。子类中的值总是覆盖 `AppController` 中的值。
```

##### 控制器动作

```php
控制器动作负责将请求参数转换成对提交请求的浏览器/用户的响应。
```

```php
CakePHP 会渲染一个以动作名称的转换(inflected)版本命名的视图
```

```
控制器动作通常用 set() 创建上下文[即类似assign]，供 View 用来渲染视图。视图文件名是动作名称的下划线分隔的小写格式
```

##### 生命周期

```php
Controller::beforeFilter()  在控制器每个动作之前执行
Controller::beforeRender() 在控制器动作逻辑之后、但在视图渲染之前被调用
Controller::afterFilter() 在每个动作之后、且在渲染完成之后才调用
```

##### 控制器方法

1. ##### 视图交互

   1. set()返回数据

      ```php
      // 首先从控制器传递数据:
      
      $this->set('color', 'pink');
      // 然后，在视图里，可以使用该数据:
      <?php echo $color; ?>
      ```

      1. render()最终是渲染哪个视图文件

      > [`render()`](https://book.cakephp.org/2/zh/controllers.html#Controller::render) 方法在每个请求的控制器动作结束时会被自动调用
      >
      > [`$layout`](https://book.cakephp.org/2/zh/views.html#View::$layout) 参数用来指定视图渲染所使用的布局。

      $this->autoRender=false 关闭默认渲染视图文件

      ```php
      // 渲染 /View/Elements/ajaxreturn.ctp 中的元素
      $this->render('/Elements/ajaxreturn');
      ```

      

2. ##### 渲染某个视图

   > 渲染制定视图文件

   ```php
   class PostsController extends AppController {
       public function my_action() {
           $this->render('custom_file');
       }
   }
   
   //会渲染 app/View/Posts/custom_file.ctp，而不是 app/View/Posts/my_action.ctp
   ```

   > 渲染插件视图文件： 
   >
   > `$this->render('插件名称.插件控制器/定制视图文件')`

   ```php
   class PostsController extends AppController {
       public function my_action() {
           $this->render('Users.UserDetails/custom_file');
       }
   }
   //渲染 app/Plugin/Users/View/UserDetails/custom_file.ctp
   ```

   

##### 流程控制[重定向]

1. redirect

```php
public function place_order() {
    // 这里是确认订单的逻辑
    if ($success) {
        return $this->redirect(
            array('controller' => 'orders', 'action' => 'thanks')
        );
    }
    return $this->redirect(
        array('controller' => 'orders', 'action' => 'confirm')
    );
}
$this->redirect('/orders/thanks');
$this->redirect('http://www.example.com');
$this->redirect(array('action' => 'edit', $id));
```

   2.flash(类似success）

##### 	其他方法

> `Controller::constructClasses`**(**): 加载控制器需要的模型

> ```php
> Controller::referer: 返回当前请求的引用网址
> ```

> `Controller::postConditions`**()**

```php
//此方法来将一组提交(POSTed)的模型数据(来自与 HtmlHelper 助件兼容的输入)转换 成一组模型的查找条件。这个函数提供了一个建立搜索逻辑的快捷方式

public function index() {
    $conditions = $this->postConditions($this->request->data);
    $orders = $this->Order->find('all', compact('conditions'));
    $this->set('orders', $orders);
}
```

> `Controller::paginate`()
>
> 用于将模型读取的结果分页

> ```php
> Controller::requestAction
> ```

> ```php
> Controller::loadModel  加载模型类进行使用
> 
> $this->loadModel('Article');
> $recentArticles = $this->Article->find(
>     'all',
>     array('limit' => 5, 'order' => 'Article.created DESC')
> );
> 
> $this->loadModel('User', 2);
> $user = $this->User->read();
> ```

##### 控制器变量

```php
 class RecipesController extends AppController {
    public $uses = array('Recipe', 'User');
    public $helpers = array('Js');
    public $components = array('RequestHandler');
}
```

> $name  设置控制器名称

```php
// $name 解决返回的类名并不遵循CamelCase（驼峰命名法）格式
class RecipesController extends AppController {
   public $name = 'Recipes';
}
```

> ##### $helpers 指定其他的helper助手函数

```php
//默认情况下 HtmlHelper 、 FormHelper 和 SessionHelper 助件都是可用的
public $helpers = array('Html','Ajax','Javascript');   
```

>  $uses 是否载入新的model进行使用

```php
var $uses = array('Fraggle','Smurf');  
public $uses = array('Fraggle','Smurf'); 
使用：
$this->Fraggle
$this->Smurf
 //注意：你仍然需要在$uses数组里包含你的Fraggle model，即使在这之前它（Fraggle model）就已经自动可用了。
```

> $layout: 设置该变量的值为你想为这个controller使用的layout（布局）的名字

> $autoRender :将这个变量设为false能让action在自动render之前自动停止

```php
$beforeFilter
//如果你想要一些代码在每次action被调用的时候执行（并且在该action任何代码运行之前），使用$beforeFilter。这个功能用来访问控制是非常完美的－你可以在任何action执行之前检查当前用户的权限。只要将这个变量设置成一个数组，该数组包含了你想要运行的controller action（在其他action运行之前执行的action）
    
var $beforeFilter = array('checkAccess');  

function checkAccess()  
{  
    //Logic to check user identity and access would go here....  
} 
```

> $components: 用来装载你需要的组件

```
$components = array('acl');
```

##### 控制器参数

> $this->params用来获取传递到controller的数据，以及提供对当前请求信息的访问

```php
$this->request->params['static']
```

![params](https://upload-images.jianshu.io/upload_images/3098875-d9fb633a12b1a428.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)