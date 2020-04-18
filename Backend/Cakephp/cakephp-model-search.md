##### Model

> model使用的表名为model名字的复数形式
>
> 例如："User"--->"users"
>
> Model的名字：[驼峰法命名 单数形式]。

###### 功能

> 包含数据校验规则，关联关系以及针对这张表的业务逻辑

```PHP
class User extends AppModel  
{  
    //始终包含此变量是一种很好的做法。
    var $name = 'User'; 
 
 	//用于验证
    var $validate = array(); 
 
  	//定义关联。
    var $hasMany = array(
        'Image' => array('className' => 'Image'));  
  
	//包含自己的函数：
    function makeInactive($uid)  
    {  
        //Put your own logic here...  
    }  
} 
```

##### 常用获取数据方法
###### find

> find方法只返回list中的第一个结果数组。
>
> 所有检索数据方法中的多功能机器。`$type` 参数值可以是 `'all'` 、`'first'` 、 `'count'` 、 `'list'` 、`'neighbors'` 或 `'threaded'`，或者任何自定义查询类型。切记 `$type` 是大小写敏感的

```php
  string $conditions  检索条件,就是sql中where子句
  array $fields  检索属性，就是投影，指定所有你希望返回的属性
  string $order 排序属性
  int $recursive  递归 当设为大于1的整数时，将返回该model关联的其他对象	
```

```php
array(
    'conditions' => array('Model.field' => $thisValue), //查询条件数组
    'recursive' => 1, //整型
    //字段名数组
    'fields' => array('Model.field1', 'DISTINCT Model.field2'),
    //定义排序的字符串或者数组
    'order' => array('Model.created', 'Model.field3 DESC'),
    'group' => array('Model.field'), //用来分组(*GROUP BY*)的字段
    'limit' => n, //整型
    'page' => n, //整型
    'offset' => n, //整型
    'callbacks' => true //其他值可以是 false, 'before', 'after'
)
```

###### find('all',array())

> findAll=>find('all',array()) 返回所有结果集数组,是所有的`find()` 方法的变体、包括 `paginate` 方法使用的内在机制

```php
  string $conditions  检索条件,就是sql中where子句
  array $fields  检索属性，就是投影，指定所有你希望返回的属性
  string $order 排序属性
  int $limit 结果集数据条目限制
  int $page 结果集分页index，默认为返回第一页
  int $recursive  递归 当设为大于1的整数时，将返回该model关联的其他对象
```

```php
//写法
$this->HomeStyle->find('all',
              array(
             "order"=>array("sort"=>"desc"),
              "fields"=>array("id","name"),
             "limit"=>15,
             "page"=> 1,
             "recursive"=>-1)
)
```

> 返回格式如下：

```php
Array
(
    [0] => Array
        (
            [ModelName] => Array
                (
                    [id] => 83
                    [field1] => value1
                    [field2] => value2
                    [field3] => value3
                )
 
            [AssociatedModelName] => Array
                (
                    [id] => 1
                    [field1] => value1
                    [field2] => value2
                    [field3] => value3
                )
 
        )
)
```

###### find('first',$params)

>  只返回一条记录

```php
public function some_function() {
    // ...
    $semiRandomArticle = $this->Article->find('first');
    $lastCreated = $this->Article->find('first', array(
        'order' => array('Article.created' => 'desc')
    ));
    $specificallyThisOne = $this->Article->find('first', array(
        'conditions' => array('Article.id' => 1)
    ));
    // ...
}
```

> 返回格式

```php
Array
(
    [ModelName] => Array
        (
            [id] => 83
            [field1] => value1
            [field2] => value2
            [field3] => value3
        )
 
    [AssociatedModelName] => Array
        (
            [id] => 1
            [field1] => value1
            [field2] => value2
            [field3] => value3
        )
)
```



###### findAllBy字段属性 

> findAllBy<fieldName>
>   string $value 
>
> 返回是一个数组,返回结果数组的格式与 `find('all')` 的返回值格式一样

```php
//控制器下使用：
$this->loadModel("Customer");
$this->Session->delete('sms-code');
$this->Customer->recursive = -1;
$result = $this->Customer->findByAccount($userName);

//产品关联文章
$this->loadModel('PostProduct');
$this->PostProduct->unbindModel(array('belongsTo'=> array('Product')));
$posts = $this->PostProduct->findAllByProductId($product['Product']['id']);
```

![image.png](https://i.loli.net/2020/04/14/eovEjM5iJDCVntU.png)

###### findBy

![image.png](https://i.loli.net/2020/04/15/uPH9p1C4sqkmazj.png)

###### find('count',$params) 

> findCount  =>find('count')
> string $conditions 
>
> 返回一个整数值。即select count

```php
$total=$this->TopicLaw->find('count',array('conditions'=>$conditions));
```

###### find('list')

> 返回一个索引数组，可用于任何需要列表的场合，比如在生成填充 select 输入元素的列表框

```php
public function some_function() {
    // ...
    $allArticles = $this->Article->find('list');
    $pending = $this->Article->find('list', array(
        'conditions' => array('Article.status' => 'pending')
    ));
    $allAuthors = $this->Article->User->find('list');
    $allPublishedAuthors = $this->Article->find('list', array(
        'fields' => array('User.id', 'User.name'),
        'conditions' => array('Article.status !=' => 'pending'),
        'recursive' => 0
    ));
    // ...
}
```

> 返回格式

```json
Array
(
    //[id] => 'displayValue',
    [1] => 'displayValue1',
    [2] => 'displayValue2',
    [4] => 'displayValue4',
    [5] => 'displayValue5',
    [6] => 'displayValue6',
    [3] => 'displayValue3',
)
```

###### find('threaded')

>  返回一个嵌套数组，适用于想使用模型数据的`parent_id` 字段来建立嵌套结果的情况

```php
public function some_function() {
    // ...
    $allCategories = $this->Category->find('threaded');
    $comments = $this->Comment->find('threaded', array(
        'conditions' => array('article_id' => 50)
    ));
    // ...
}
```

> 返回格式

```php
Array
(
    [0] => Array
    (
        [ModelName] => Array
        (
            [id] => 83
            [parent_id] => null
            [field1] => value1
            [field2] => value2
            [field3] => value3
        )
 
        [AssociatedModelName] => Array
        (
            [id] => 1
            [field1] => value1
            [field2] => value2
            [field3] => value3
        )
 
        [children] => Array
        (
            [0] => Array
            (
                [ModelName] => Array
                (
                    [id] => 42
                    [parent_id] => 83
                    [field1] => value1
                    [field2] => value2
                    [field3] => value3
                )
 
                [AssociatedModelName] => Array
                (
                    [id] => 2
                    [field1] => value1
                    [field2] => value2
                    [field3] => value3
                )
 
                [children] => Array
                (
                )
            )
            ...
        )
    )
)
```

###### find('neighbors')

> `find('neighbors', $params)` 方法执行的查找与 'first' 类似, 但返回查询的记录的前一条和后一条记录

```php
public function some_function() {
    $neighbors = $this->Article->find(
        'neighbors',
        array('field' => 'id', 'value' => 3)
    );
}
```

> 返回格式

```php
Array
(
    [prev] => Array
    (
        [ModelName] => Array
        (
            [id] => 2
            [field1] => value1
            [field2] => value2
            ...
        )
        [AssociatedModelName] => Array
        (
            [id] => 151
            [field1] => value1
            [field2] => value2
            ...
        )
    )
    [next] => Array
    (
        [ModelName] => Array
        (
            [id] => 4
            [field1] => value1
            [field2] => value2
            ...
        )
        [AssociatedModelName] => Array
        (
            [id] => 122
            [field1] => value1
            [field2] => value2
            ...
        )
    )
)
```

##### 树状结构

###### generateTreeList

> generateTreeList
> 	$Model 
>
>  ​    string|array $conditions = *null* 
>
>  ​    string $keyPath = *null* 
>
> ​	string $valuePath = *null* ,
>
> ​	string $spacer = '_' ,
>
> ​	integer $recursive = *null*
>
> $keyPath 和 $valuePath是model中用来填充结果集中key和value的属性

```php
$categories = $this->ContractCategory->generateTreeList(null, null, null, '&nbsp;&nbsp;&nbsp;', 1);
```

##### 自定义执行语句

###### query/execute

> 使用query(string $query)和execute(string $query)方法
>
> 执行自定义的sql语句或者是出于性能上的考虑要优化sql语句	
>
> execute方法无返回值，适用于执行脚本

##### 保存数据-save/saveField

> 当需要保存model对象时（使用持久化），你需要提供如下形式的数据给save()方法

```php
Array  
(  
    [ModelName] => Array  
        (  
            [fieldname1] => 'value'  
            [fieldname2] => 'value'  
        )  
)  
//第一种
    页面上的元素的name被设置成date[Modelname][fieldname]形式
//第二种
    $html->input('Model/fieldname')

saveField  保存单一属性
  string $name 
  string $value 
```

##### Model变量

1. $primaryKey
   如果主键字段不为'id'，COC无法发挥的时候，你可以通过该变量来指定主键字段名字。

2. $recursive
   指定了model级联关联对象的深度。

3. $transactional
   决定Model是否允许事务处理，true 或者 false。注意，仅在支持事务的数据库上有效。

4. $useTable
   和$primaryKey一样，如果你的表名不能和model匹配的话，而且你也不想或者不能修改表名，则可以通过该变量来指定数据表名。

5. $validate
   该变量为一个array，表示一组校验规则，详细请参见Chapter 12。

6. $useDbConfig 

   切换数据库连接 默认是default

   

7. ```php
   $recursive = 0 Cake只会检索Group对象
   $recursive = 1 Cake会检索包含User对象的Group对象
   $recursive = 2 Cake会检索完成的包含Article的Group对象
   ```

##### 关联对象

```php
hasOne 一对一
hasMany 多对一
belongsTo 一对一
hasAndBelongsToMany 多对多
```

> 命名约定中我们需要考虑的有这3个内容，外键，model名字，表名
>
> 外键：[单数形式的model名字]_id 假设在"authors"表中有Post的外键关联，则外键字段名字应该为 "post_id"。
> 表名：[复数形式的model名字] 表名必须为model名字的复数形式，例如："posts" "authors"。
> Model的名字：[驼峰法命名 单数形式]。

> User[主表]和Profile「关联表」
>
> 一对一关联

```php
/app/models/user.php hasOne  
<?php  
class User extends AppModel  
{  
    var $name = 'User';  
    var $hasOne = array('Profile' =>  
                        array('className'    => 'Profile',  
                              'conditions'   => '',  
                              'order'        => '',  
                              'dependent'    =>  true,  
                              'foreignKey'   => 'user_id'  
                        )  
                  );  
}  
?>  
```

1. className (required)：关联对象的类名，上面代码中我们设为'Profile'表示关联的是Profile对象。
2. conditions: 关联对象的选择条件，（译注：类似hibernate中的formula）。具体到我们的例子来看，假设我们仅关联Profile的header color为绿色的文件记录，我们可以这样定义conditions，"Profile.header_color = 'green'"。
3. order: 关联对象的排序方式。假设你希望关联的对象是经过排序的，你可以为order赋值，就如同SQL中的order by子句："Profile.name ASC"。
4. dependent：这是个布尔值，如果为true，父对象删除时会级联删除关联子对象。在我们的Blog中，如果"Bob"这个用户被删除了，则关联的Profile都会被删除。类似一个外键约束。
5. foreignKey：指向关联Model的外键字段名。仅在你不遵循Cake的命名约定时需要设置。

>    目前是在User表 =>hasOne 查询表中没外键
>
>    拥有外键的一方使用belongsTo 查询表中含有外键



> 一对多关联
>
> User和Comment:一个用户对应多条评论

```php
/app/models/user.php hasMany  
<?php  
class User extends AppModel  
{  
    var $name = 'User';  
    var $hasMany = array('Comment' =>  
                         array('className'     => 'Comment',  
                               'conditions'    => 'Comment.moderated = 1',  
                               'order'         => 'Comment.created DESC',  
                               'limit'         => '5',  
                               'foreignKey'    => 'user_id',  
                               'dependent'     => true,  
                               'exclusive'     => false,  
                               'finderQuery'   => ''  
                         )  
                  );  
  
    // Here's the hasOne relationship we defined earlier...  
    var $hasOne = array('Profile' => 
                        array('className'    => 'Profile', 
                              'conditions'   => '', 
                              'order'        => '', 
                              'dependent'    =>  true, 
                              'foreignKey'   => 'user_id'  
                        )  
                  );  
}  
?>  
```

1. className (required)：关联对象类名。
2. conditions: 关联对象限定条件。
3. order: 关联对象排序子句。
4. limit：因为是一对多关系，所以可以通过limit来限定检索的关联对象数量。比如我们可以只关联5条评论记录。
5. foreignKey：外键字段名。仅当不遵循命名约定时起用。
6. dependent：是否级联删除。（该动作可能会造成数据的误删除，请谨慎设定）
7. exclusive：如果设为true，所有的关联对象将在一句sql中删除，model的beforeDelete回调函数不会被执行。但是如果没有复杂的逻辑在级联删除中，这样的设定会带来性能上的优势。（译注：Cake的确方便，但是使用时一定要记住控制sql语句发送数量）
8. finderQuery：定义一句完整的sql语句来检索关联对象，能够对关联规则进行最大程度上的控制。当关联关系特别复杂的时候，比如one table - many model one model - many table的情况下，Cake无法准确的替你完成映射动作，需要你自己来完成这个艰巨的任务。

##### 保存关联对象

1. 当关联的两个对象都没有持久化（即未保存在数据库中）

```php
//------------Post Comment都没有持久化------------ 
//We can save the Post data:   
//it should be in $this->data['Post']  
        $this->Post->save($this->data);  
//Now, we'll need to save the Comment data  
//But first, we need to know the ID for the   
//Post we just saved...  
        $post_id = $this->Post->getLastInsertId();  
//Now we add this information to the save data  
//and save the comment.  
        $this->data['Comment']['post_id'] = $post_id;  
//Because our Post hasMany Comments, we can access  
//the Comment model through the Post model:  
        $this->Post->Comment->save($this->data); 
```

​	2.现有的一篇Post添加一个新的Comment记录

```php
1.通过URL来传递这个参数或者
2.使用一个Hidden字段来提交隐藏元素命名（如果你使用HtmlHelper）来正确提交：
假设日志的ID我们这样来命名$post['Post']['id']
<?php echo $html->hidden('Comment/post_id', array('value' => $post['Post']['id'])); ?>
Post对象的ID可以通过$this->data['Comment']['post_id']来访问，同样的通过$this->Post->Comment->save($this->data)也能非常简单的调用。
```

```php
$this->data['Comment']['post_id'] = $post_id;  
//Because our Post hasMany Comments, we can access  
//the Comment model through the Post model:  
$this->Post->Comment->save($this->data);  
```

> 保存多个子对象时，采用一样的方法，只需要在一个循环中调用save()方法就可以了（但是要记住使用Model::create()方法来初始化对象）
>
> 无论是belongsTo, hasOne还是hasMany关联，在保存关联子对象时候都要记住把父对象的ID保存在子对象中

##### 关联模型bindModel()

##### 解绑模型unbindModel()

> 前提是你的数据库表中的外键关联等已经正确设置

```php
定义Leader Follower 一对多关联
class Leader extends AppModel  
{  
    var $name = 'Leader';  
  
    var $hasMany = array(  
        'Follower' => array(  
            'className' => 'Follower',  
            'order'     => 'Follower.rank'  
        )  
    );  
} 

class Follower extends AppModel  
{  
    var $name = 'Follower';  
}  
  
```

使用

```php
动态解除关联：
$this->Leader->findAll(); //查询leader和follower所有结果集 
$this->Leader->unbindModel(array('hasMany' => array('Follower')));  
$this->Leader->findAll(); //查询只有leader所有结果集 
//注意：unbindModel方法只作用一次，第二次find方法调用时则仍然是关联关系有效的 
$this->Leader->findAll(); //查询出leader和follower所有结果集 

动态绑定关联
$this->Leader->bindModel(  
    array('hasMany' => array( 
        'Principle' => array( 
            'className' => 'Principle' 
        ) 
    ) 
  ) 
); 
$this->Leader->findAll(); //查询出leader和Principle所有结果集
```

