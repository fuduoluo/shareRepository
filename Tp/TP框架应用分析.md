##### Auth类初始化

[初始化对象区别](https://blog.csdn.net/qq_38287952/article/details/82669217)

```php
/**
* 初始化
* @access public
* @param array $options 参数
* @return Auth
*/
public static function instance($options = [])
{
    if (is_null(self::$instance)) {
        self::$instance = new static($options);
    }
    return self::$instance;
}
//new static和new self区别
```

1. new static()是在php5.3版本引入的新特性
2. 无论是 new static 还是 new self() 都是 new 一个对象
3. 区别：new出来的到底是同一个类的实例还是不同类的实例
4. 只有在继承中才能体现出来、如果没有任何继承、那么二者没有任何区别
5. new self() 返回的实例是不会变的，无论谁去调用，都返回的一个类的实例，而 new static则是由调用者决定的



```php
/**
 * @param $name
 * @param $arguments
 * @return FormBuilder
 */
public static function __callStatic($name, $arguments)
{
    return call_user_func_array([FormBuilder::instance(), $name], $arguments);
}
//call_user_func_array与call_user_func区别
//魔术函数：__callStatic与__call区别
```

