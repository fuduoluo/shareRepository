#### EFA后台使用TP框架归纳[不完全]

> [查询某个字段的值](https://www.kancloud.cn/manual/thinkphp5_1/354000)

```php
//返回某个字段的值，查询结果不存在，返回 null
$power = $partModel->where(['id' => $part])->value('power');
```

> [模板继承](https://www.kancloud.cn/manual/thinkphp5_1/354080)

> 每个区块由{block} {/block}标签组成。
> 指定name属性来标识当前区块的名称，在当前模板是唯一

```php
定义基类模板，子模板[引用位置]进行继承
//比如定义基类模板
/*template/base.html*/
{block name="title"}
	<title>{$web_title}</title>
{/block}

//子模板继承[test.html]
{extend name="template:base"/}
{block name="title"}
	继承基类进行新增补充内容
{/block}

```

```php
extend标签的用法和include标签一样，也可以加载其他模板【进行继承基类也可以进行补充内容】
加载template文件夹下的文件如：base,footer模板
加载外部文件:
{block name='footer'}
	{include file:="template:footer"}
{/block}

{extend name="template:base"/}

```

[URL生成](https://www.kancloud.cn/manual/thinkphp5_1/353977)

```php
Url::build('地址表达式',['参数'],['URL后缀'],['域名'])
助手函数url
url('地址表达式',['参数'],['URL后缀'],['域名'])
// 生成index模块 blog控制器的read操作 URL访问地址
Url::build('index/blog/read', 'id=5&name=thinkphp');
// 使用助手函数
url('index/blog/read', 'id=5&name=thinkphp');
Url::build('school/login/index')
```

> 验证场景

[验证场景手册](https://www.kancloud.cn/manual/thinkphp5_1/354104)

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
    
    // edit 验证场景定义
    public function sceneEdit()
    {
    	return $this->only(['name','age'])
        	->append('name', 'min:5')
            ->remove('age', 'between')
            ->append('age', 'require|max:100');
    }    
}
//使用
model->sceneEdit->check($data);
```

```
方法名	描述
only	场景需要验证的字段
remove	移除场景中的字段的部分验证规则
append	给场景中的字段需要追加验证规则
```

> cache使用

```
// 切换到redis操作获取token值
$user = Cache::store('redis')->get($token);
```

> 更新

- 实例化模型后调用`save`方法表示新增；
- 查询数据后调用`save`方法表示更新；
- `save`方法传入更新条件后表示更新；

![image.png](https://upload-images.jianshu.io/upload_images/3098875-c5bf6a4db86c1598.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

> 过滤非数据库字段

```php
$user = new User;
// post数组中只有name和email字段会写入
$user->allowField(['name','email'])->save($_POST);
```

> [分页参数](https://www.kancloud.cn/manual/thinkphp5_1/354120)

![image.png](https://upload-images.jianshu.io/upload_images/3098875-3349ec0396790bbc.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

![image.png](https://upload-images.jianshu.io/upload_images/3098875-880725978abd2661.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

```php
$list = Db::name('user')->where('status',1)->paginate(10,true,[
    'type'     => 'bootstrap',
    'var_page' => 'page',
]);
```

> [fetch](https://www.kancloud.cn/manual/thinkphp5_1/354066)

```php
return $this->fetch('index', [
'name'  => 'ThinkPHP',
'email' => 'thinkphp@qq.com'
]);
```

> [自定义命令行命令](https://blog.csdn.net/fangkang7/article/details/83860387)
>
> [参考2](https://www.cnblogs.com/oujianjun/p/9832311.html)

```php
return [
	'Redisc' => 'app\common\command\Redisc',
	'PushMessage' => 'app\common\command\PushMessage',
];
//使用php think make Redisc
//执行app\common\command\Redisc代码
//通过编写bat脚本开机启动redisc拼单支付redis功能
```

```bat
@echo off
cd D:/phpstudy_pro/WWW/efa/public/
php index.php index/redis_go_up/index
::pause
```

##### 控制器依赖注入【减少new一个对象步骤】

```php
    public function live_list(Video $module){
        $request = $this->request;
        $list = $module->getVideoList($request->param());
        return $this->fetch('live_list', [
            'title' => '直播课上传列表',
            'list' => $list,
        ]);
     
    }
//如Video会自动实例化，$module是video当前请求对象实例
```

> 5.0.2版本开始，如果依赖注入的类有定义一个可调用的静态`invoke`方法，则会自动调用invoke方法完成依赖注入的自动实例化。
>
> `invoke`方法的参数是当前请求对象实例

##### TP[模板使用函数.....参考这里](https://www.cnblogs.com/lovebing/p/6625871.html)

[文档....点击这里](https://www.kancloud.cn/manual/thinkphp5/125005)

> 变量输出使用的函数可以支持内置的PHP函数或者用户自定义函数，甚至是静态方法
>
> {:function()}

```php
//函数会按照从左到右的顺序依次调用
{:build_heading()}
{:substr(strtoupper(md5($name)),0,3)}
//支持多个函数过滤，多个函数之间用"|"分割
{$name|md5|strtoupper|substr=0,3}
```

