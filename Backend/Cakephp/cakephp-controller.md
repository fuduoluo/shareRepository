[cakePHP中文文档](http://docs.30c.org/cakephp/)

[cakePHP2.x 菜谱](https://www.bookstack.cn/read/cakephp-v2.0-zh/6722eb60985ce027.md)

> 原则：控制器可以被认为是模型和视图之间的中间人。应当保持控制器瘦，而模型胖。这将使代码更容易复用、更容易测试

应用程序中的控制器继承于 `AppController` 类，而 `AppController` 类又继承于核心的 [`Controller`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller) 类。`AppController` 类定义在`/app/Controller/AppController.php` 中，它所包含的方法是在应用程序所有的控制器之间共享的。

##### App控制器

> `AppController` 控制器是应用程序中所有控制器的父类,扩展了核心库的controller类

注解

CakePHP 将下列变量从 `AppController` 合并到你应用程序的控制器:

- [`$components`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller::$components)
- [`$helpers`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller::$helpers) 已默认的 Html 和 Form 助件
- [`$uses`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller::$uses)

控制器使用的组件(*components*)和助件(*helpers*)列表被特别处理。对这两个列表， `AppController` 中的值会和控制器子类中的(同名)数组合并。子类中的值总是覆盖 `AppController` 中的值。
##### 控制器参数

> 当一个请求提交给 CakePHP 应用程序时，CakePHP 的 [`Router`](https://www.bookstack.cn/read/cakephp-v2.0-zh/b94e944c6f90e898.md#Router) 和`Dispatcher` 类使用 [路由的配置](https://www.bookstack.cn/read/cakephp-v2.0-zh/b94e944c6f90e898.md#routes-configuration) 来查找和创建正确的控制器。CakePHP 把所有重要的请求信息放在`$this->request` 属性中。
>
> $this->params用来获取传递到controller的数据，以及提供对当前请求信息的访问

```php
$this->request->params['static']
```

![params](https://upload-images.jianshu.io/upload_images/3098875-d9fb633a12b1a428.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

##### 控制器动作

> 控制器动作负责将请求参数转换成对提交请求的浏览器/用户的响应。
>
> 根据约定，CakePHP 会渲染一个以动作名称的转换(inflected)版本命名的视图。回到我们在线面包店的例子，我们的 RecipesController 也许会有 view()，share() 和search() 动作。控制器会是 /app/Controller/RecipesController.php

> 视图文件将会是 `app/View/Recipes/view.ctp` 、`app/View/Recipes/share.ctp` 和 `app/View/Recipes/search.ctp` 。视图文件名是动作名称的下划线分隔的小写格式。

```php
# /app/Controller/RecipesController.php
class RecipesController extends AppController {
    public function view($id) {
        // 这里是动作逻辑 ...
    }
 
    public function share($customerId, $recipeId) {
        // 这里是动作逻辑 ...
    }
 
    public function search($query) {
        // 这里是动作逻辑 ...
    }
}
```

> 控制器动作通常用 set() 创建上下文[即类似assign]，供 View 用来渲染视图。视图文件名是动作名称的下划线分隔的小写格式

> 跳过默认视图渲染过程

##### 请求生命周期回调

- `Controller::``beforeFilter`()
- 这个函数在控制器每个动作之前执行。这里可以方便地检查有效的会话，或者检查用户的权限。
- `Controller::``beforeRender`()
- 在控制器动作逻辑之后、但在视图渲染之前被调用。这个回调不常用，但如果你在一个动作结束前自己调用 [`render()`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller::render)，就可能会需要。
- `Controller::``afterFilter`()
- 在每个动作之后、且在渲染完成之后才调用。

> 总结：		

```php
Controller::beforeFilter()  在控制器每个动作之前执行
Controller::beforeRender() 在控制器动作逻辑之后、但在视图渲染之前被调用
Controller::afterFilter() 在每个动作之后、且在渲染完成之后才调用
```

##### 控制器方法

1. ##### 视图交互

   1. `set()`返回数据

      ```php
      // 首先从控制器传递数据:
      
      $this->set('color', 'pink');
      // 然后，在视图里，可以使用该数据:
      <?php echo $color; ?>
      你为蛋糕选择了 <?php echo $color; ?> 色的糖霜。
      ```

      [`set()`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller::set) 方法也接受关联数组作为其第一个参数。

      ```php
      $data = array(
          'color' => 'pink',
          'type' => 'sugar',
          'base_price' => 23.95
      );
      // 使 $color，$type 和 $base_price 能够被视图使用:
      $this->set($data);
      ```

      

   2. `render()`最终是渲染哪个视图文件

      > [`render()`](https://book.cakephp.org/2/zh/controllers.html#Controller::render) 方法在每个请求的控制器动作结束时会被自动调用,执行所有的视图逻辑(使用你用 [`set()`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller::set) 方法给出的数据)，将视图放入它的布局([`$layout`](https://www.bookstack.cn/read/cakephp-v2.0-zh/2a729776dd3c46a9.md#View::$layout))中，并把它提供给最终用户
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

1. redirect 这个方法的第一个参数接受的是 CakePHP 相对网址的形式

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

> $url 参数可以使用相对或者绝对路径:

```php
$this->redirect('/orders/thanks');
$this->redirect('http://www.example.com');
```

> 也可以传递数据给动作:

```php
$this->redirect(array('action' => 'edit', $id));
```

> 跳转到referer页面

```php
$this->redirect($this->referer());
```

> 支持命名的参数
>
> 跳转类似地址：http://www.example.com/orders/confirm/product:pizza/quantity:5

```php
$this->redirect(array(
    'controller' => 'orders',
    'action' => 'confirm',
    'product' => 'pizza',
    'quantity' => 5)
);
使用查询字符串(query string)和哈希(hash)的例子像这样:
$this->redirect(array(
    'controller' => 'orders',
    'action' => 'confirm',
    '?' => array(
        'product' => 'pizza',
        'quantity' => 5
    ),
    '#' => 'top')
);
```

> 生成的网址为:`http://www.example.com/orders/confirm?product=pizza&quantity=5#top`

   2.flash(类似success）:[`flash()`](https://www.bookstack.cn/read/cakephp-v2.0-zh/1a905e28961c3e87.md#Controller::flash) 方法的不同之处在于，它会显示一条信息，然后才引导用户到另一个网址。

> flash第一个参数应当为要显示的信息，而第二个参数是 CakePHP 的相对网址。CakePHP 会显示 `$message` 并停留 `$pause` 秒，再引导用户跳转。



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

##### 控制器属性

> 变量每个都会与它们继承的值合并，所以没有必要(比如)再次声明 Form 助件，或者任何你在 `AppController` 中已经声明的东西

```php
 class RecipesController extends AppController {
    public $name = "Recipes";
    public $uses = array('Recipe', 'User');
    public $helpers = array('Js');
    public $components = array('RequestHandler');
}
```

> ##### `$name`  设置控制器名称,解决返回的类名并不遵循CamelCase（驼峰命名法）格式

```php
class RecipesController extends AppController {
   public $name = 'Recipes';
   public function test(){
       $this->模型名称去调用
   }
}
```

> ##### `$helpers` 指定其他的helper助手函数

```php
//默认情况下 HtmlHelper 、 FormHelper 和 SessionHelper 助件都是可用的
public $helpers = array('Html','Ajax','Javascript');   
视图中使用:$this->{助件名称}
1.$this->HTML->CSS
2.$this->HTML->script
```

>  ##### `$uses` 是否载入新的model进行使用

```php
var $uses = array('Fraggle','Smurf');  
public $uses = array('Fraggle','Smurf'); 
使用：
$this->Fraggle
$this->Smurf
 //注意：你仍然需要在$uses数组里包含你的Fraggle model，即使在这之前它（Fraggle model）就已经自动可用了。
```

> ##### `$layout`: 设置该变量的值为你想为这个controller使用的layout（布局）的名字
>
> $this->layout= 'view';

> ##### `$autoRender` :将这个变量设为false能让action在自动render之前自动停止
>
> $this->autoRender= false;

```php
$beforeFilter
//如果你想要一些代码在每次action被调用的时候执行（并且在该action任何代码运行之前），使用$beforeFilter。这个功能用来访问控制是非常完美的－你可以在任何action执行之前检查当前用户的权限。只要将这个变量设置成一个数组，该数组包含了你想要运行的controller action（在其他action运行之前执行的action）
    
var $beforeFilter = array('checkAccess');  

function checkAccess()  
{  
    //Logic to check user identity and access would go here....  
} 
```

> ##### `$components`: 用来装载你需要的组件

```php
public $components = array('acl');
```

##### 组件

##### 配置组件：用`$components` 数组、或者控制器的 `beforeFilter()` 方法来进行配置

```php
class PostsController extends AppController {
    public $components = array(
        'Auth' => array(
            'authorize' => array('controller'),
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login'
            )
        ),
        'Cookie' => array('name' => 'CookieMonster')
    );
  //控制器的 beforeFilter() 方法中配置组件  
 	public function beforeFilter() {
    $this->Auth->authorize = array('controller');
    $this->Auth->loginAction = array(
        'controller' => 'users',
        'action' => 'login'
    );
 
    $this->Cookie->name = 'CookieMonster';
}
```

##### 使用组件: `确保不要给组件和模型起相同的名字`。

```php
class PostsController extends AppController {
    public $components = array('Session', 'Cookie');
 
    public function delete() {
        if ($this->Post->delete($this->request->data('Post.id')) {
            $this->Session->setFlash('Post deleted.');
            return $this->redirect(array('action' => 'index'));
        }
    }
```



##### 动态加载组件：不会调用它的 initialize 方法。如果调用的组件有这个方法，就需要在加载后手动调用

```php
$this->OneTimer = $this->Components->load('OneTimer');
$this->OneTimer->getTime();
```

##### [组件回调](https://www.bookstack.cn/read/cakephp-v2.0-zh/fffb32bab683ea7e.md)

##### 创建组件：所有的组件必须继承自 [`Component`](https://www.bookstack.cn/read/cakephp-v2.0-zh/fffb32bab683ea7e.md#Component)。如果不这样做，就会导致异常。

> 首先要创建一个新组件的文件和类。创建`app/Controller/Component/MathComponent.php` 文件。组件的基本构造如下:

```php
App::uses('Component', 'Controller');
class MathComponent extends Component {
    public function doComplexOperation($amount1, $amount2) {
        return $amount1 + $amount2;
    }
}
```

> 控制器中使用：

```php
/* 让新的组件可以通过 $this->Math 访问，以及标准的 $this->Session */
public $components = array('Math', 'Session');
```

##### 组件中使用组件: 与控制器中引入的组件不同，组件中的组件不会触发回调

```php
// app/Controller/Component/CustomComponent.php
App::uses('Component', 'Controller');
class CustomComponent extends Component {
    // 你的组件使用的其它组件
    public $components = array('Existing');
 
    public function initialize(Controller $controller) {
        $this->Existing->foo();
    }
 
    public function bar() {
        // ...
   }
}
 
// app/Controller/Component/ExistingComponent.php
App::uses('Component', 'Controller');
class ExistingComponent extends Component {
 
    public function foo() {
        // ...
    }
}
```



##### 发送文件【2.3版本之前有效】

###### 使用：

![image.png](https://i.loli.net/2020/03/19/Bar6uSTieUG3tVj.png)

之前自己创建一个新的视图类文件我是放在lib/cake/view中[其他位置我使用会报错，可能我姿势不对！]

控制器中：

```php
$this->viewClass = 'Media';//框架自带
//Media指命名视图类名称【可以自定义】

$params = array(
    'id'        => $contract['Contract']['doc_name'],//下载文件名称【用于拼接路径】
    'name'      => $contract['Contract']['title'],//下载文件名
    'download'  => true,//是否开启下载
    'extension' => $contract['Contract']['doc_ext'],//文件后缀
    'mimeType'  => array(
        $contract['Contract']['doc_ext'] => 'application/octet-stream',//接受文件格式
    ),
    'path'      => $path,//需要下载文件路径
);
$this->set($params);
```