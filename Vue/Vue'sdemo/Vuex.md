#### Vuex概念，内容，主题

![vuex](https://github-imglib-1255459943.cos.ap-chengdu.myqcloud.com/vue-11-6.png)

> :desert_island: Vue 组件中是通过`prop`属性和自定义事件`$on`、`$emit`可以实现父子组件间的通信
>
> evenBus待了解：事件中线

[new  Promise介绍](https://segmentfault.com/a/1190000007032448)

##### 概念：

> 官方说法：Vuex 是一个专为 Vue.js 应用程序开发的**状态管理模式**
>
> 网上说法：在state中定义了一个数据之后，你可以在所在项目中的任何一个组件里进行获取、进行修改，并且你的修改可以得到全局的响应变更
>
> 自己理解：管理全局数据状态供跨组件和页面使用，并会全局响应更新

##### 内容：

> 包括：**store  state, getters, actions, mutations**

##### 主题：

> :construction_worker: Vuex在SPA单页面组件，解决跨页面,跨组件之间的数据通信

[新手入门....点击这里](https://segmentfault.com/a/1190000009404727)   [新手入门....点击这里](https://segmentfault.com/a/1190000015782272) 

##### :mag_right: 单向数据流的方式:

```js
(Action) -> Mutation -> Store -> update view
```

概念理解 [参考....点击这里](https://zhuanlan.zhihu.com/p/24357762)

> :hammer:State 就是数据库。
>
> :hammer:Mutations 就是我们把数据存入数据库的 API，用来修改state 的。
>
> :hammer:getters 是我们从数据库里取数据的 API
>
> :hammer:actions 是比如后端从前端拿到了数据，总要做个处理吧，处理完了再存到数据库中。其实这就是action的过程

##### :label:Store

> 类似仓库，所有 State 的集合

```js
/ 引入Vuex库
Vue.use(Vuex);

// 创建一个Store
const store = new Vuex.Store({
  // 设置状态
  state: {
    count: 0
  },
  // Mutation用于更新状态
  mutations: {
    increment(state) {
      state.count++;
    }
  }
});

// Vuex通过Store选项，提供了一种机制将状态从根组件“注入”到每一个子组件中（需调用 ）：
const app = new Vue({
  // 其他选项省略
  // 把store对象提供给“store”选项，这可以把store的实例注入所有的子组件
  store
});
```



##### :label: state:

> 类似data，理解为一个绑定到界面或组件的状态变量，就像是`data`中的变量

> State 通常是指全局的应用状态

```js
new Vue({
  // state
  data() {
    return {
      count: 0
    };
  }
});
```

##### :label:解决store臃肿问题

> Module使用

> :grey_question: 相同的一些状态命名发生了冲突，要如何解决呢？
>
> :hammer_and_wrench: 我们还可以通过添加`namespaced: true`的方式，来创建带命名空间的模块

```js
const store = new Vuex.Store({
  modules: {
    account: {
      namespaced: true, // 带命名空间
      // 模块内容（module assets）
      state: { ... }, // 模块内的状态已经是嵌套的了，使用namespaced属性不会对其产生影响
      // 可以拥有自己的Action和Mutation
      actions: {
        login () { ... } // ->使用 $store->dispatch('account/login')
      },
      mutations: {
        login () { ... } // -> $store->commit('account/login')
      },
      // 嵌套模块
      modules: {
        // 继承父模块的命名空间
        myPage: {
          state: { ... }
        },
        // 进一步嵌套命名空间
        posts: {
          namespaced: true,
          state: { ... }
        }
      }
    }
  }
})
```

##### :label:mutation

> 类似于一个守卫，所有的状态变更都必须来自 Mutation 都是同步函数[多个state属性]

```js
const store = new Vuex.Store({
  state: {
    count: 1
  },
  mutations: {
    // 每个 Mutation 都有一个字符串的事件类型 type 和一个回调函数 handler
    // 这个回调函数就是我们实际进行状态更改的地方
    increment(state) {
      // 变更状态
      state.count++;
    }
  }
});

// 要调用 Mutation handler，你需要以相应的 type 调用 store.commit 方法
store.commit("increment");
```

##### :label:actions:

> 异步操作  【多个mutations】

> :biking_man: 例如从后台接口拉取的数据更新，这种情况下我们可以使用 Action

> :gear: Mutation，Action 的不同之处在于：
> (1) Action 提交的是 Mutation，而不是直接变更状态。
> (2) Action 可以包含任意异步操作[一个线程处理多个任务且是并行处理，是在同步操作全部执行完后才去执行异步操作]。

```js
const store = new Vuex.Store({
  state: {
    count: 0
  },
  mutations: {
    increment(state) {
      state.count++;
    }
  },
  actions: {
    // Action 函数接受一个与 store 实例具有相同方法和属性的 context 对象
    increment(context) {
      // 因此可以调用 context.commit 提交一个 mutation
      context.commit("increment");
      // 其他的，也可以通过 context.state 来获取 state
    }
  }
});
```



##### :label:getters

> `getters` 和 vue 中的 `computed` 类似 , 都是用来计算 state 然后生成新的数据 ( 状态 ) 的

比如现在想获取到一个相反的状态或者是反转的state中的show

```js
//计算属性：
computed(){
    not_show(){
        return !this.$store.state.dialog.show;
    }
}

//Vuex:
getters:{
    not_show(state){//这里的state对应着上面这个state
        return !state.show;
    }
}
//组件上使用：
this.$store.getters.not_show
或者
store.getters.not_show
```

> :alien: 注意 : `$store.getters.not_show` 的值是不能直接修改的 , 需要对应的 state 发生变化才能修改

##### 	:label: mapState、mapGetters、mapActions

> ​	解决类似 `$store.state.dialog.show` 、`$store.dispatch('switch_dialog')` 这种写法又长又臭 , 很不方便 

```js
<template>
  <div>
    <el-dialog :visible.sync="show">
      <span>Vuex 学习</span>
    </el-dialog>
    <el-dialog :visible.sync="switch_dialog_one">
      <span>vuex 學習</span>
    </el-dialog>
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  computed: {
    //这里的三点叫做 : 扩展运算符
    ...mapState({
      // show: state => state.dialog.show,
      // switch_dialog_one: state => state.dialog.switch_dialog_one
    }),
    show: {
      get() {
        return this.$store.state.dialog.show;
      },
      set(val) {
        this.$store.state.dialog.show = val;
      }
    },
    switch_dialog_one: {
      get() {
        return this.$store.state.dialog.switch_dialog_one;
      },
      set(val) {
        this.$store.state.dialog.switch_dialog_one = val;
      }
    }
  }
};
</script>
```

> 出现问题：Computed property "switch_dialog_one" was assigned to but it has no setter

[解决：添加set方法](https://segmentfault.com/a/1190000018127192?utm_source=tag-newest)

> :beach_umbrella: mapGetters、mapActions 和 mapState 类似 , `mapGetters` 一般也写在 `computed` 中 , `mapActions` 一般写在 `methods` 中。

[mapGetters等具体用法...点击整这里](https://blog.csdn.net/dkr380205984/article/details/82185740)

未完待补充.......