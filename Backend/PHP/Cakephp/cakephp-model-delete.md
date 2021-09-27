##### delete方法

```php
delete(int $id = null, boolean $cascade = true);
```

> 删除指定$id的记录。默认情况下，会级联删除依赖于该记录的所有记录。
>
> Model::delete()支持使用行为及回调方法

例如，当删除一个User，User关联了许多条Recipe记录(使用 'hasMany' 或'hasAndBelongsToMany' 来关联Recipes)。

- 如果$cascade设置为true,并且被关联的模型中dependent属性值为true,删除User的同时会删除关联的Recipe记录。
- 如果$cascade设置为false,删除User不会删除关联的Recipe记录。

```php
$this->Comment->delete($this->request->data('Comment.id'));
```

##### deleteAll

```php
deleteAll(mixed $conditions, $cascade = true, $callbacks = false)
```

> 将删除匹配条件的所有记录。
>
> `$conditions` 作为条件可以是SQL语句片段或数组形式。

- **conditions** 匹配的条件
- **cascade** 布尔型，设置true，会导致级联删除
- **callbacks** 布尔型, 执行回调函数
  执行成功返回true，失败返回false。

```php
// 与 find() 方法类似，删除满足条件的记录
$this->Comment->deleteAll(array('Comment.spam' => true), false);
```

注解：

如果通过回调方法和(或)级联方式删除记录，这往往会导致更多的查询。在deleteAll() 中匹配的记录被删除前，关联会重置。如果是使用bindModel()或unbindModel()来改变关联，应该设置 **reset** 为 `false`