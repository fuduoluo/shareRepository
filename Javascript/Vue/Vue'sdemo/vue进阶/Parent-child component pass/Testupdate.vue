<template>
  <section class="todoapp">
    <header class="header">
      <h1>todos</h1>
      <!-- 输入备忘，使用 v-model 绑定 newTodo -->
      <!-- 监听 keyup 事件，同时使用修饰器 .enter，按 Enter 键时事件才触发 -->
      <input
        class="new-todo"
        placeholder="你接下来要做什么?"
        v-autofocus
        v-model="newTodo"
        @keyup.enter="addTodo"
      />
      
    </header>
    <section class="main" v-show="todos.length">
      <!-- <input class="toggle-all" type="checkbox" v-model="allDone"> -->
      <ul class="todo-list">
        <!-- 查看所有备忘 -->
        <!-- v-for 遍历所有备忘，key 绑定备忘 id，class 绑定样式 -->
        <!-- 查看所有备忘 -->
        <!-- v-for 遍历所有备忘，key 绑定备忘 id，class 绑定样式 -->
        <li
          v-for="todo in todos"
          class="todo"
          :key="todo.id"
          :class="{ completed: todo.completed }"
        >
          <!-- 使用 todo-item 组件 -->
          <!-- “双向绑定”备忘内容 title 和备忘已完成状态 completed -->
          <!-- 监听 delete 事件 -->
          <todo-item
            v-bind:title.sync="todo.title"
            v-bind:completed.sync="todo.completed"
            @delete="removeTodo(todo.id)"
          ></todo-item>
        </li>
      </ul>
      <footer class="footer" v-show="todos.length">
        <span class="todo-count">
          <!-- remaining 计算剩余的未完成的数量，pluralize 用来过滤单位是否要负数 -->
          <strong>{{ remaining }}</strong>
          {{ remaining | pluralize }} left
        </span>
        <!-- 当有已完成的备忘时，一键移除已完成按钮出现 -->
        <button
          class="clear-completed"
          @click="removeCompleted"
          v-show="todos.length > remaining"
        >Clear completed</button>
      </footer>
    </section>
  </section>
</template>

<script>
import TodoItem from "./TodoItem";
export default {
  name: "Test",
  components: {
    "todo-item": TodoItem
  },
  data() {
    return {
      id: 0,
      todos: [], // 所有的备忘列表
      newTodo: "", // 新增的备忘
      editedTodo: {} // 修改中的备忘
    };
  },
  computed: {
    //   计算属性使用
    remaining() {
      return this.todos.filter(x => !x.completed).length;
    }
  },
  filters: {
    // 过滤器
    // 计算单位
    pluralize(num) {
      // 如果是多个，则加复数
      return num > 1 ? "items" : "item";
    }
  },
  methods: {
    //  添加数据
    addTodo() {
      if (!this.newTodo) {
        return;
      }
      this.todos.unshift({
        id: this.id++,
        title: this.newTodo,
        completed: false
      });
      this.newTodo = "";
    },
    removeTodo(todo) {
      // 匹配 id 找出该备忘，然后移除
      const index = this.todos.findIndex(x => x.id === todo);
      this.todos.splice(index, 1);
    },
    removeCompleted() {
      this.todos = this.todos.filter(x => !x.completed);
    }
  },
  directives: {
    autofocus: {
      // 被绑定元素插入父节点时调用 (仅保证父节点存在，但不一定已被插入文档中)
      inserted: function(el) {
        // el: 指令所绑定的元素，可以用来直接操作 DOM
        el.focus();
      }
    }
  }
};
</script>
<style>
/* 第三方引入css */
@import "https://unpkg.com/todomvc-app-css@2.1.0/index.css";
</style>
