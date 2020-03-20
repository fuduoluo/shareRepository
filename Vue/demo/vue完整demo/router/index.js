import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Test from '@/components/Test'
import Todo from "@/pages/Todo.vue"
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: "/",
      component: Todo,
      name: "Todo"
    },
    // 通配符 * 会匹配所有路径
    {
      path: "*",
      redirect: {
        name: "Todo"
      }
    }
  ]
})
