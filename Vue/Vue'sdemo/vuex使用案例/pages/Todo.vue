<template>
  <section class="todoapp">
    <header class="header">
      <h1>TodosList</h1>
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
      <transition-group
        name="staggered-fade"
        tag="ul"
        v-bind:css="false"
        v-on:before-enter="beforeEnter"
        v-on:enter="enter"
        v-on:leave="leave"
        class="todo-list"
      >
        <!-- 查看所有备忘 -->
        <!-- v-for 遍历所有备忘，key 绑定备忘 id，class 绑定样式 -->
        <!-- 查看所有备忘 -->
        <!-- v-for 遍历所有备忘，key 绑定备忘 id，class 绑定样式 -->
        <li
          v-for="todo in computedTodos"
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
      </transition-group>
      <footer class="footer" v-show="todos.length">
        <span class="todo-count">
          <!-- remaining 计算剩余的未完成的数量，pluralize 用来过滤单位是否要负数 -->
          <strong>{{ remaining }}</strong>
          {{ remaining | pluralize }} 未处理
        </span>
        <!-- 当有已完成的备忘时，一键移除已完成按钮出现 -->
        <ul class="filters">
          <!-- exact 设置精确匹配，active-class 设置激活状态 -->
          <li>
            <router-link
              :to="{ query: { state: '' } }"
              active-class="selected"
              exact
              >全部</router-link
            >
          </li>
          <li>
            <router-link
              :to="{ query: { state: 'active' } }"
              active-class="selected"
              exact
              >未完成</router-link
            >
          </li>
          <li>
            <router-link
              :to="{ query: { state: 'completed' } }"
              active-class="selected"
              exact
              >已完成</router-link
            >
          </li>
        </ul>
        <button
          class="clear-completed"
          @click="removeCompleted"
          v-show="todos.length > remaining"
        >
         清除已完成
        </button>
      </footer>
    </section>
  </section>
</template>

<script>
import TodoItem from "../components/vueDialog/TodoItem";
import Velocity from "velocity-animate";
// let id = 1;
export default {
  components: {
    "todo-item": TodoItem
  },
  data() {
    return {
      id:0,
      // 初始化的时候，获取下本地的缓存
      todos: JSON.parse(localStorage.getItem("todos") || "[]"), // 所有的备忘列表
      newTodo: "", // 新增的备忘
      editedTodo: {} // 修改中的备忘
    };
  },
  watch: {
    // 侦听 todos 的变化
    todos(newVal) {
      // 每次更新写入缓存
      localStorage.setItem("todos", JSON.stringify(newVal));
    }
  },
  computed: {
    remaining() {
      return this.todos.filter(x => !x.completed).length;
    },
    computedTodos() {
      const state = this.$route.query.state;
      const filterTodo = this.todos.filter(x => {
        if (state === "active") {
          return !x.completed;
        } else if (state === "completed") {
          return x.completed;
        } else {
          return true;
        }
      });
      // 过滤展示匹配的内容
      return filterTodo.filter(item => {
        return (
          item.title.toLowerCase().indexOf(this.newTodo.toLowerCase()) !== -1
        );
      });
    }
  },
  filters: {
    // 过滤器
    // 计算单位
    pluralize(num) {
      // 如果是多个，则加复数
      return num > 1 ? "个" : "个";
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
      // const index = this.todos.findIndex(function(x) {
      //   return x.id === todo;
      // });
      // this.todos.splice(index, 1);
    },
    removeCompleted() {
      this.todos = this.todos.filter(x => !x.completed);
      // this.todos = this.todos.filter(function(x){
      //         return !x.completed
      // });
    },
    // 进入中
    beforeEnter(el) {
      el.style.opacity = 0;
      el.style.height = 0;
    },
    enter(el, done) {
      // 设置延时
      var delay = el.dataset.index * 150;
      setTimeout(function() {
        // 更新元素样式
        Velocity(el, { opacity: 1, height: "58px" }, { complete: done });
      }, delay);
    },
    // 离开时
    leave(el, done) {
      // 设置延时
      var delay = el.dataset.index * 150;
      setTimeout(function() {
        // 更新元素样式
        Velocity(el, { opacity: 0, height: 0 }, { complete: done });
      }, delay);
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
@import "https://unpkg.com/todomvc-app-css@2.1.0/index.css";
*, ::after, ::before {
    box-sizing: inherit;
}
</style>