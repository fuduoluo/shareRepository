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

##### 控制器属性[组件、助件、$uses]

> $name  设置控制器名称

```php
// $name 控制器属性用法示例
class RecipesController extends AppController {
   public $name = 'Recipes';
}
```

> ### $components、$helpers 和 $uses

```php
Controller::$uses    使用$uses当前控制器的(主要)模型名称一定也要包括在内
//控制器中不想使用(任何)模型
public $uses = array()
    
Controller::$helpers  默认情况下 HtmlHelper 、 FormHelper 和 SessionHelper 助件都是可用的
 
 class RecipesController extends AppController {
    public $uses = array('Recipe', 'User');
    public $helpers = array('Js');
    public $components = array('RequestHandler');
}
```

