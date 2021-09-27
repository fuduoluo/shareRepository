#### 模型属性

##### useDbConfig

`useDbConfig` 属性为指定数据库连接的名称的字符串，用来绑定模型类和关联的数据库表。

数据库配置文件位于/app/Config/database.php。

`useDbConfig` 属性的默认值是 'default'。

```php
class Example extends AppModel {
    public $useDbConfig = 'alternate';
}
```

##### useTable

useTable` 属性指定数据库表的名称。默认情况下，模型会使用模型类名的小写复数形式。可以设定为其他表，如果希望模型不使用数据库表，也可以设置为`false

```php
class Example extends AppModel {
    public $useTable = 'exmp'; // 此模型使用数据库表 'exmp'
}
```

##### tablePrefix

模型使用的表的前缀。表的前缀最初设置在数据库连接文件 /app/Config/database.php 中，在模型中设置 `tablePrefix` 属性覆盖默认值

```php
class Example extends AppModel {
    public $tablePrefix = 'alternate_'; // 将寻找表 'alternate_examples'
}
```

##### recursive

recursive 属性决定 CakePHP 用 `find()` 和 `read()` 方法读取关联数据时的深度

设想应用程序中有组，组属于域(*domain*)，组中有很多用户，每个用户又有很多文章。可以根据调用 $this->Group->find() 要返回的数据量来设置 $recursive 为不同的值：

- -1 CakePHP 只读取 Group 数据，没有 join。
- 0 CakePHP 读取 Group 数据，它的域(*domain*)
- 1 CakePHP 读取 Group 数据，它的域(*domain*)，及其关联的用户(*User*)
- 2 CakePHP 读取 Group 数据，它的域(*domain*)，关联的用户(*User*)，以及用户关联的文章
  不要设置比所需要的更大的值。让 CakePHP 读取不会使用的数据会不必要地减慢应用程序。也要注意默认的 recursive 默认级别是 1。

注解

如果想把 $recursive 与 `fields` 功能结合，必须手动把包含必要外键字段的列加入到 `fields` 数组中。在上面的例子中，这意味着需要加入 `domain_id`。

建议的 recursive 级别应当为 -1。这可以防止读取不必要的、甚至不需要的关联数据。

##### order

任何 find 操作的数据的默认排序

```php
$order = "field"
$order = "Model.field";
$order = "Model.field asc";
$order = "Model.field ASC";
$order = "Model.field DESC";
$order = array("Model.field" => "asc", "Model.field2" => "DESC");
```

##### name

模型的名称。如果不在模型文件中指定，这会被构造函数设为类名。

```php
class Example extends AppModel {
    public $name = 'Example';
}
```

