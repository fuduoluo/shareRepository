##### [ES6中的export、import、export default区别](https://blog.csdn.net/qq_27552077/article/details/72902926)

> export[强调模块]：一个模块可以理解为一个类，要使用引用文件内部变量只能使用export关键字输出。

```js
//输出变量用法1
export var firstName = 'Michael';

//输出变量用法2
var firstName = 'Michael';
export {firstName};//可以多个

//输出函数用法1
export function multiply(x, y) {
  return x * y;
};
//输出函数用法2
function v1() { ... }
function v2() { ... }

//设置别名
export {
  v1 as streamV1
};

//输出类
export default class { ... }
```

> import[强调JS文件]：import命令接受一对大括号，里面指定要从其他模块导入的变量名。大括号里面的变量名，`必须与被导入模块（profile.js）对外接口的名称相同`。 如果想为输入的变量重新取一个名字，import命令要使用as关键字，将输入的变量重命名

> import是静态执行，不能使用表达式和变量

> import不重复加载

```js
import {firstName, lastName, year} from './profile';
import { lastName as surname } from './profile';//设置别名
function setName(element) {
  element.textContent = firstName + ' ' + lastName;
}

```

> export default：为了给用户提供方便，让他们不用阅读文档就能加载模块，就要用到export default命令，为模块指定默认输出

```js
//默认输入该匿名函数
export default function () {
  console.log('foo');
}

使用import必须为其指定别名
import customName from './export-default';//不能使用花括号
customName(); // 'foo'
```

```js
// modules.js
function add(x, y) {
  return x * y;
}
export {add as default};
// 等同于
// export default add;

// app.js
import { default as xxx } from 'modules';
// 等同于
// import xxx from 'modules';
正是因为export default命令其实只是输出一个叫做default的变量，所以它后面不能跟变量声明语句。

// 正确
export var a = 1;

// 正确
var a = 1;
export default a;
```