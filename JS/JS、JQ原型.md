[函数定义....点击这里](https://www.runoob.com/js/js-function-definition.html)

#### 构造函数

> 在 JavaScript 中，用 new 关键字来调用的函数，称为构造函数。构造函数首字母一般大写

#### **函数定义**

> JavaScript 使用关键字 **function** 定义函数。
>
> 函数可以通过声明定义，也可以是一个表达式

```
//声明
function functionName(parameters) {
  执行的代码
}
//表达式
var x = function (a, b) {return a * b};
```

#### [类的定义](https://www.w3school.com.cn/js/pro_js_object_defining.asp)

> **3种：工厂，构造，原型**

> 主要是助于理解小程序编码格式和梳理：主要说明原型方式定义JS类
>
> :aerial_tramway: 原型方式利用了对象的 prototype 属性，可以把它看成创建新对象所依赖的原型

![ES主要使用以下这种方式编码定义使用类](https://i.loli.net/2020/03/28/GiTgwsXQDR45FLt.png)

##### [帮你彻底搞懂JS中的prototype、__proto__与constructor（图解）](https://blog.csdn.net/cc18868876837/article/details/81211729#5__29)

![image.png](https://i.loli.net/2020/03/28/dSRtGBu3pbNniyU.png)

![之间关系如下](https://i.loli.net/2020/03/28/JnoumkGsxylSFhH.png)

#### __proto__属性

![image.png](https://i.loli.net/2020/03/28/w2RLIBudhamNkJO.png)

#### prototype属性

![image.png](https://i.loli.net/2020/03/28/17Ku3IlLDWwaSOH.png)

#### constructor属性

![image.png](https://i.loli.net/2020/03/28/AgCkEYxVpROXKni.png)

##### [可以拓展下--JavaScript中的new操作符](https://blog.csdn.net/cc18868876837/article/details/103149502)

##### jQuery.fn 

> 相当于$.fn:实例化一个JQ类的对象，理解为可以类

```js
//这是源码：
jQuery.fn = jQuery.prototype = {
　　　init: function( selector, context ) {//….
//……
};
```

##### jQuery.extend(object)

> jQuery类添加类方法，可以理解为添加静态方法

```js
jQuery.extend({
    min: function(a, b) { return a < b ? a : b; },
    max: function(a, b) { return a > b ? a : b; }
});
jQuery.min(2,3); //  2 
jQuery.max(4,5); //  5
```

##### Objectj Query.extend( target, object1, *[objectN]*)

> 用一个或多个其他对象来扩展一个对象，返回被扩展的对象

```js
var settings = { validate: false, limit: 5, name: "foo" }; 
var options = { validate: true, name: "bar" }; 
jQuery.extend(settings, options); 
结果：settings == { validate: true, limit: 5, name: "bar" }
```

##### jQuery.fn.extend(object);

> jQuery.prototype进得扩展即$.fn，就是为jQuery类添加“成员函数”

```js
$.fn.extend({          
    alertWhileClick:function() {            
          $(this).click(function(){                 
                 alert($(this).val());           
           });           
     }       
});       
$("#input1").alertWhileClick(); // 页面上为
```

