[参考链接...点击](https://segmentfault.com/a/1190000022296485)

#### 精准分享关键代码

##### 分享单行代码

> 在url加上`#L行号`

```js
http://www.google.com/#L520
```

##### 分享多行代码

> `url后面加上`#L`开始行号`-L`结束行号

#### 通过提交的msg自动关闭issues

有人在https://github.com/AlloyTeam/AlloyTouch/issues/6提了个issues,但是你修改代码解决掉了可以通过以下方式，就会自动关闭

```js
fix  https://github.com/AlloyTeam/AlloyTouch/issues/6
```

```js
close
closes
closed
fixes
fixed
resolve
resolves
resolved
```

#### 通过HTML方式嵌入Github

```js
<iframe src="//ghbtns.com/github-btn.html?  
    user=alloyteam&repo=alloytouch&type=watch&count=true"   
    allowtransparency="true"   
    frameborder="0" scrolling="0"   
    width="110" height="20">  
</iframe>
```

效果如下：

![img](https://segmentfault.com/img/bVbFIuP)

### gitattributes设置项目语言

![img](https://segmentfault.com/img/bVbFIuQ)

如上图所示，github会根据相关文件代码的数量来自动识别你这个项目是HTML项目还是Javascript项目。

这就带来了一个问题，比如AlloyTouch最开始被识别成HTML项目。

因为HTML例子比JS文件多。怎么办呢？gitattributes来帮助你搞定。在项目的根目录下添加如下.gitattributes文件便可

新建文件内容如下：

```js
*.html linguist-language=JavaScript
```

主要意思是把所有html文件后缀的代码识别成js文件

### 查看自己项目的访问数据

在自己的项目下，点击Graphs，然后再点击Traffic如下所示：

![img](https://segmentfault.com/img/bVbFIuR)

Referring sites代表大家都是从什么网站来到你的项目的，Popular content代表大家经常看你项目的哪些文件

