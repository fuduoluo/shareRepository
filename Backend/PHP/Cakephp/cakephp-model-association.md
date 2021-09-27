#### 模型关联

| 关系   | 关联类型            | 例子                       |
| ------ | ------------------- | -------------------------- |
| 一对一 | hasOne              | 一个用户只有一份个人资料。 |
| 一对多 | hasMany             | 一个用户可以有多份食谱。   |
| 多对一 | belongsTo           | 多份食谱属于同一个用户。   |
| 多对多 | hasAndBelongsToMany | 多份食谱有且属于多种原料。 |

##### 定义关联

```php
class User extends AppModel {
    public $hasOne = 'Profile';
    public $hasMany = array(
        'Recipe' => array(
            'className' => 'Recipe',
            'conditions' => array('Recipe.approved' => '1'),
            'order' => 'Recipe.created DESC'
        )
    );
}
```

> 'Recipe' 是所谓的 '别名(*Alias*),**每个模型的别名在应用程序中必须唯一**
>
> CakePHP 会自动在关联模型对象之间建立连接

###### 注解

记住，关联定义是'单向的'。如果定义了 User hasMany Recipe(用户有很多菜谱)，这对 Recipe 模型没有任何影响。需要定义 Recipe belongsTo User(菜谱属于用户)才能从 Recipe 模型访问 User 模型

##### hasOne

> **设置 User 模型以 hasOne 关系关联到 Profile 模型**

**hasOne:***另一个* 模型包含外键。例如：本例中profiles 表需要包含一个叫做 user_id 的字段

| 关系                                     | 数据结构          |
| ---------------------------------------- | ----------------- |
| Apple hasOne Banana (苹果有一个香蕉)     | bananas.apple_id  |
| User hasOne Profile (用户有一份个人资料) | profiles.user_id  |
| Doctor hasOne Mentor (博士有一位导师)    | mentors.doctor_id |

在/app/Model/Profile.php 文件中定义 Profile 模型

```php
class User extends AppModel {
    public $hasOne = array(
        'Profile' => array(
            'className' => 'Profile',
            'conditions' => array('Profile.published' => '1'),
            'dependent' => true
        )
    );
}
```

> 参数

- **className**: 与当前模型关联的模型的类名。如果你要定义 'User hasOne Profile(用户有一份个人资料)' 的关系，className 键应当是 'Profile'。
- **foreignKey**: 另一模型中的外键名。如果需要定义多个 hasOne 关系，这个键非常有用。其默认值为当前模型的以下划线分隔的单数模型名称，并后缀以 '_id'。在上面的例子中，就默认为 'user_id'。
- **conditions**: 兼容 find() 的条件数组或者是 SQL 字符串，例如array('Profile.approved' => true)。
- **fields**: 在读取关联模型数据时，需要读取的字段的列表。默认返回所有的字段。
- **order**: 兼容 find() 的排序子句或者 SQL 字符串，例如array('Profile.last_name' => 'ASC')。
- **dependent**: 当 dependent 键被设置为 true，并且调用模型的 delete() 方法时参数 cascade 也被设置为 true，关联模型的记录也会一起被删除。在本例中，我们将其设置为 true 将导致删除一个 User 时也会删除她/他关联的 Profile

> 使用：

```php
//调用 $this->User->find() 的结果示例。
 
Array
(
    [User] => Array
        (
            [id] => 121
            [name] => Gwoo the Kungwoo
            [created] => 2007-05-01 10:31:01
        )
    [Profile] => Array
        (
            [id] => 12
            [user_id] => 121
            [skill] => Baking Cakes
            [created] => 2007-05-01 10:31:01
        )
)
```

##### belongsTo

> 现在我们可以从 User 模型访问 Profile 的数据，让我们在 Profile 模型中定义belongsTo 关联以获取相关的 User 数据。belongsTo 关联是 hasOne 和 hasMany关联的自然补充：它让我们可以从另一个方向查看数据。

遵循约定

**belongsTo:***当前模型* 包含外键。

| 关系                                      | 数据结构          |
| ----------------------------------------- | ----------------- |
| Banana belongsTo Apple (香蕉属于苹果)     | bananas.apple_id  |
| Profile belongsTo User (个人资料属于用户) | profiles.user_id  |
| Mentor belongsTo Doctor (导师属于博士)    | mentors.doctor_id |

小技巧：

如果一个模型(表)包含一个外键，它 belongsTo 另一个模型(表)。

在 /app/Model/Profile.php 文件中的 Profile 模型定义关联

```php
class Profile extends AppModel {
    public $belongsTo = 'User';
}
class Profile extends AppModel {
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
}
```

> 参数：

- **className**: 与当前模型关联的模型的类名。如果你要定义 'Profile belongsToUser (个人资料属于用户)' 的关系，className 键应当是 'User'。
- **foreignKey**: 当前模型中的外键。如果需要定义多个 belongsTo 关系，这特别方便。其默认值为另一模型的以下划线分隔的单数模型名，后缀以 `_id`。
- **conditions**: 兼容 find() 的条件数组或者 SQL 字符串，例如`array('User.active' => true)`。
- **type**: SQL 查询使用的 join 类型。默认为 'LEFT'，这也许不能在所有情况下都符合你的需要。在你想要获取主模型和关联模型的所有记录、或者什么都不要时，'INNER'(当和某些条件一起使用时)也许会有帮助。
- **fields**: 在读取关联模型数据时，需要读取的字段的列表。默认返回所有的字段。
- **order**: 兼容 find() 的排序子句或者 SQL 字符串，例如`array('User.username' => 'ASC')`。
- **counterCache**: 如果此键的值设置为 true，当你在做 `save()` 或者`delete()` 操作时，关联模型将自动递增或递减外键关联的表的 "[以下划线分隔的单数模型名称]_count" 列的值。如果它是一个字符串，那这就是要使用的列名。计数器列的值表示关联记录的行数。也可以通过使用数组指定多个计数器缓存，详见[多个计数器缓存(counterCache)](https://www.bookstack.cn/read/cakephp-v2.0-zh/32938c7f147845b3.md#multiple-countercache)。
- **counterScope**: 可选的用于更新计数器缓存字段的条件数组

```php
//调用 $this->Profile->find() 的结果示例。
 
Array
(
   [Profile] => Array
        (
            [id] => 12
            [user_id] => 121
            [skill] => Baking Cakes
            [created] => 2007-05-01 10:31:01
        )
    [User] => Array
        (
            [id] => 121
            [name] => Gwoo the Kungwoo
            [created] => 2007-05-01 10:31:01
        )
)
```

##### hasMany

> 定义一个 "User hasMany Comment (用户有多条评论)" 的关联

遵循约定

| 关系                                  | 数据结构        |
| ------------------------------------- | --------------- |
| User hasMany Comment (用户有多条评论) | Comment.user_id |

在 /app/Model/User.php 文件的 User 模型中定义关系

```
class User extends AppModel {
    public $hasMany = 'Comment';
}
class User extends AppModel {
    public $hasMany = array(
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'user_id',
            'conditions' => array('Comment.status' => '1'),
            'order' => 'Comment.created DESC',
            'limit' => '5',
            'dependent' => true
        )
    );
}
```

- **className**: 与当前模型关联的模型的类名。如果你定义了 'User hasManyComment (用户有多条评论)' 关系，className 键的值应当为 'Comment'。
- **foreignKey**: 另一个模型中的外键名。如果需要定义多个 hasMany 关系，这特别方便。其默认值为当前模型以下划线分隔的单数模型名称后缀以 '_id'。
- **conditions**: 兼容 find() 的条件数组或者 SQL 字符串，例如array('Comment.visible' => true)。
- **order**: 兼容 find() 的排序子句或者 SQL 字符串，例如array('Profile.last_name' => 'ASC')。
- **limit**: 要返回的关联数据的最大行数。
- **offset**: 在读取和关联之前，要跳过的关联数据行数(在当前查询条件和排序的情况下)。
- **dependent**: 当 dependent 设置为 true，就可以进行模型的递归删除。在本例中，当关联的 User 记录被删除时，Comment 记录也将被删除。
- **exclusive**: 当 exclusive 设置为 true，将调用 deleteAll() 进行模型的递归删除，而不是分别删除每条数据。这大大提高了性能，但可能并非在所有情况下都是最好的选择。
- **finderQuery**: 可供 CakePHP 用于读取关联模型记录的完整 SQL 查询语句。这应当用于要求高度定制结果的场合。如果构建的查询语句要求使用关联模型 ID，可以在查询语句中使用特殊标记 `{$**cakeID**$}`。例如，如果 Apple 模型 hasMany Orange，查询语句就应当象这样：`SELECT Orange.* from oranges as Orange WHERE Orange.apple*id = {$\*_cakeID**$};` 。

```php
//调用 $this->User->find() 的结果示例。
 
Array
(
    [User] => Array
        (
            [id] => 121
            [name] => Gwoo the Kungwoo
            [created] => 2007-05-01 10:31:01
        )
    [Comment] => Array
        (
            [0] => Array
                (
                    [id] => 123
                    [user_id] => 121
                    [title] => On Gwoo the Kungwoo
                    [body] => The Kungwooness is not so Gwooish
                    [created] => 2006-05-01 10:31:01
                )
            [1] => Array
                (
                    [id] => 124
                    [user_id] => 121
                    [title] => More on Gwoo
                    [body] => But what of the 'Nut?
                    [created] => 2006-05-01 10:41:01
                )
        )
)
```

