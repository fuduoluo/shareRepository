##### 关于重写elementUi的message组件问题,实际开发使用!!

在项目中看到应用到elementUI,源码如下：

```js
//mian.js
import ElementUI from 'element-ui';
Vue.use(ElementUI);
//只需要Message组件，所以只有引入一个Message
import { Message } from 'element-ui';

// 为了实现Class的私有属性[声明一个全局唯一的变量，showMessage相当于一个注释]
const showMessage = Symbol('showMessage')
/**
 *  重写ElementUI的Message
 *  single默认值true，因为项目需求，默认只弹出一个，可以根据实际需要设置
 */
class DonMessage {
  success (options, single = true) {
    this[showMessage]('success', options, single)
  }
  warning (options, single = true) {
    this[showMessage]('warning', options, single)
  }
  info (options, single = true) {
    this[showMessage]('info', options, single)
  }
  error (options, single = true) {
    this[showMessage]('error', options, single,-1)
  }

  [showMessage] (type, options, single) {
    if (single) {
      // 判断是否已存在Message
      if (document.getElementsByClassName('el-message').length === 0) {
        Message({
          type: type,
          message: options,
          offset:-1
        });
      }
    } else {
      Message({
        type: type,
        message: options,
        offset:-1
      });
    }
  }
}
```

[网上重写逻辑](https://blog.csdn.net/yusirxiaer/article/details/101450941?depth_1-utm_source=distribute.pc_relevant.none-task&utm_source=distribute.pc_relevant.none-task)

##### 熟悉TS为前提

以下是源码[不完整,自己对照查看]相关的解读：

首先呢，message组件是由typescript编写之后运行转换熟悉的js代码，关于[TS学习](https://www.tslang.cn/docs/handbook/typescript-in-5-minutes.html)

> 源码开始


```js
import Vue, {VNode} from 'vue'
//引入或加载vue对象,VNode类
```

1. [Vnode是什么东西....详细介绍在这](https://blog.csdn.net/violetjack0808/article/details/79354852)

   我大致看了下关于VNode的内容，差不多是

   1. 普通的 JavaScript Class 类
   2. 定义一堆的VNode对象属性和初始化属性来描述VNode对象[具体自己看源码吧]
   3. \* 所有对象的 `context` 选项都指向了 Vue 实例。
      \* `elm` 属性则指向了其相对应的真实 DOM 节点。
      \* DOM 中的文本内容被当做了一个只有 `text` 没有 `tag` 的节点。
      \* 像 class、id 等HTML属性都放在了 `data` 中

```tsx
//TS语法:定义类型其中一种方式：type align[别名]
export type MessageType = 'success' | 'warning' | 'info' | 'error'
//定义类型别名叫MessageType这5个，并暴露出去。
```

[TS定义类型](https://www.jianshu.com/p/a27704291a3b) [官网说明，声明合并](https://www.tslang.cn/docs/handbook/declaration-merging.html)

> “声明合并”是指编译器将针对同一个名字的两个独立声明合并为单一声明

```js
/** Message Component */
export declare class ElMessageComponent extends Vue {
  /** Close the Loading instance */
  close (): void
}
//declare class ElMessageComponent:声明这个文件所需要声明继承于VUe的ElMessageComponent组件类
//关闭正在加载的实例

export interface CloseEventHandler {
  /**
   * Triggers when a message is being closed
   *
   * @param instance The message component that is being closed
   */
  (instance: ElMessageComponent): void
}
//实例化正在关闭组件类
//void类型，它表示没有任何类型。函数没有返回值时，
//你通常会见到其返回值类型是 void，一个void类型的变量只能为它赋予undefined和null
```

```tsx
/** Options used in Message */
export interface ElMessageOptions {
  /** Message text */
  message: string | VNode
  ...
  ...
  ...
}
//定义接口参数有
/*
    offset[将距离设置到视口顶部。 默认值为20像素]:number
    onClose[关闭回调函数]::CloseEventHandler
    dangerouslyUseHTMLString[是否被视为HTML字符串]:boolean
    center[是否居中]:boolean
    showClose[是否显示关闭按钮]:boolean
    duration[时长]:number
    iconClass[图标类]:string
    type[信息类型:success等]:string
    message[显示文本]:string、VNode
    offset?:number:表示可选
*/
```

```tsx
export interface ElMessage {
  /** Show an info message */
  (text: string): ElMessageComponent
  /** Show message */
  (options: ElMessageOptions): ElMessageComponent
  /** Show a success message */
  success (text: string): ElMessageComponent
  相当于：success (text: string): message|type....
  /** Show a success message with options */
  success (options: ElMessageOptions): ElMessageComponent
}
//定义接口
```

##### 项目使用

[Symbol使用:有助于你看懂重写代码](https://www.cnblogs.com/guangzan/p/11234009.html)

[Symbol](https://www.tslang.cn/docs/handbook/symbols.html)

[ES6类的全面解析](https://www.jianshu.com/p/86267fab4878)

```js
//定义一个叫DonMessage类
//重写[解决多次弹出BUG]elementUI方法success方法
class DonMessage {
    //options是指ElMessageOptions接口类options
  success (options, single = true) {
    //进行使用
    this[showMessage]('success', options, single)
  }
  //定义对象属性名为showMessage的属性及其相关逻辑代码
   [showMessage] (type, options, single) {
    if (single) {
      // 判断是否已存在Message
      if (document.getElementsByClassName('el-message').length === 0) {
       //调用message组件
   		Message({
          type: type,
          message: options,//ElMessageComponent接口类参数
          offset:-1
        });
      }
    } else {
      Message({
        type: type,
        message: options,
        offset:-1
      });
    }
  }
}
```

