[总结这里](https://zhuanlan.zhihu.com/p/81712232)

##### 验证器定义

> 快速生成模型验证器
>
> 没有定义错误提示信息，则使用系统默认的提示信息！！！
>
> ```php
> php think make:validate index/User
> ```

```php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule =   [
        'name'  => 'require|max:25',
        'age'   => 'number|between:1,120',
        'email' => 'email',    
    ];
    protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',    
    ];
    
}
```

##### 验证数据

> 在控制器上进行验证数据

```php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
   	// 是否批量验证
    protected $batchValidate = true;
    public function index()
    {
        //第一种
        $result = $this->validate(
            [
                'name'  => 'thinkphp',
                'email' => 'thinkphp@qq.com',
            ],
            'app\index\validate\User');

        if (true !== $result) {
            // 验证失败 输出错误信息
            dump($result);
        }
        //第二种 【不含批量操作】
		$validate = new \app\index\validate\User;
        if (!$validate->check($data)) {
            dump($validate->getError());
        }
    }
}
```

> 在非控制器上验证数据--批量验证

```php
namespace app\common\service;

use app\common\validate\User as UserValidate;

class Test
{	
    public function validateTest($data)
    {
        $validata = new UserValidate;
        $result   = $validate->batch()->check($data);
        if ($result !== true) {
            dump($result);
        }
    }
}
```

##### 捕获异常

```php
// 验证失败是否抛出异常
//自动抛出think\exception\ValidateException异常或者自己捕获处理
protected $failException = true;
```
##### 自定义验证规则

```php
protected $rule = [
    'name'  =>  'checkName:thinkphp',
    'email' =>  'email',
];

protected $message = [
    'name'  =>  '用户名必须',
    'email' =>  '邮箱格式错误',
];

// 自定义验证规则-checkName(验证数据 验证规则 全部数据（数组）字段名 字段描述)
protected function checkName($value,$rule,$data=[])
{
    return $rule == $value ? true : '名称错误';
}
```

##### 验证规则

> 如果你使用了验证器的话，通常通过`rule`属性定义验证规则
>
> 如果使用的是独立验证的话，则是通过`rule`方法进行定义

> `rule`属性定义

```php
    protected $rule = [
      'name'  => 'require|max:25',
      'age'   => 'number|between:1,120',
      'email' => 'email',
    ];
使用系统内置验证规则：require  number 等
    //避免出现一个字段出现混淆采用数组进行验证
    protected $rule = [
      'name'  => ['require', 'max' => 25, 'regex' => '/^[\w|\d]\w+/'],
      'age'   => ['number', 'between' => '1,120'],
      'email' => 'email',
    ];
```

> 方法定义:手动去调用验证类进行验证

```php
$validate = new \think\Validate;
use think\validate\ValidateRule as Rule;
/*使用对象化的规则定义
$validate->rule('age', Rule::isNumber()->between([1,120]))
    ->rule([
        'name'  => Rule::isRequire()->max(25),
        'email' => Rule::isEmail(),
    ]);
*/
$validate->rule('age', 'number|between:1,120')
->rule([
    'name'  => 'require|max:25',
    'email' => 'email'
]);
$data = [
    'name'  => 'thinkphp',
    'email' => 'thinkphp@qq.com'
];

if (!$validate->check($data)) {
    dump($validate->getError());
}

//单个字段使用闭包，不再支持多个验证规则
$validate = new \think\Validate;
$validate->rule([
    'name'  => function($value) { 
        return 'thinkphp' == strtolower($value) ? true : '用户名错误';
    },
]);
```

##### 错误信息

非单独定义提示信息

```php
namespace app\index\validate;
use think\Validate;
class User extends Validate
{
    protected $rule = [
      'name'  => 'require|max:25',
      'age|年龄'   => 'number|between:1,120',
      'email' => 'email',
    ];

}
/**设置中文名称**/
年龄只能在 1 - 120 之间
/**默认输出错误信息**/
name只能在 1 - 120 之间
```

> 单独定义提示信息

```php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
      'name'  => 'require|max:25',
    ];
    protected $message = [
      'name.require' => '名称必须',
      'name.max'     => '名称最多不能超过25个字符'
    ];
}
//user控制器
    $data = [
        'name'  => 'thinkphp',
    ];
    $validate = new \app\index\validate\User;
    $result = $validate->check($data);

    if(!$result){
        echo $validate->getError();
    }
```

##### 多语言[参照fast]

[详细解释](https://www.kancloud.cn/manual/thinkphp5_1/354103)

##### 验证场景

```php
use think\Validate;
//定义验证类规则
class User extends Validate
{
    protected $rule =   [
        'name'  => 'require|max:25',
        'age'   => 'number|between:1,120',
        'email' => 'email',    
    ];
    
    protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',    
    ];
    
    protected $scene = [
        'edit'  =>  ['name','age'],
    ];
    
}
```

> 验证方法中制定验证的场景

```php
//第一种
$data = [
    'name'  => 'thinkphp',
    'age'   => 10,
    'email' => 'thinkphp@qq.com',
];
$result = $this->validate($data,'app\index\validate\User.edit');
if(true !== $result){
    // 验证失败 输出错误信息
    dump($result);
}
//第二种
$validate = new UserValidate;
if (!$validate->scene('edit')->check($data)) {
    dump($validate->getError());
}
```

> 单独定义验证类规则

[单独定义验证类场景](https://www.cnblogs.com/psnh/p/9316164.html)

##### 内置规则

[内置规则详解](https://www.kancloud.cn/manual/thinkphp5_1/354107)

##### 独立验证

[具体点击这里](https://www.kancloud.cn/manual/thinkphp5_1/354106)

##### 静态调用

[具体看这](https://www.kancloud.cn/manual/thinkphp5_1/354108)

##### 针对表单令牌进行验证

[表单令牌验证](https://www.kancloud.cn/manual/thinkphp5_1/354109)

```php
{:token()} 属于模板标签解析 具体阅读源码 
 private function parseTag(&$content)	
```