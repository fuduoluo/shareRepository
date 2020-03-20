##### TodoList案例解释：

> 源自：Github 上开源的 TodoMVC（地址：https://github.com/tastejs/todomvc）

###### 实现功能：

(1) 新增一条备忘。
(2) 修改该条备忘。
(3) 选择/全选删除某条备忘。
(4) 将某条备忘设置成已完成。
(5) 快速删除已完成的备忘

###### 效果：

![效果图](https://github-imglib-1255459943.cos.ap-chengdu.myqcloud.com/vue-8-1.png)

1. 监听键盘事件

   [详解](https://blog.csdn.net/fifteen718/article/details/80359844)

2. findIndex使用

```js
// 匹配 id 找出该备忘，然后移除
es6:
const index = this.todos.findIndex(x => x.id === todo);
this.todos.splice(index, 1);

es5:
const index = this.todos.findIndex(function(x){
    return x.id === todo;
});
this.todos.splice(index, 1);
```

