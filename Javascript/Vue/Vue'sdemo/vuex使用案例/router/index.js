import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Todo from "@/pages/Todo.vue"
import Vdialog from "@/pages/Vdialog"
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld
    },
    {
      path: "/todo",
      component: Todo,
      name: "Todo"
    },
    {
      path: "/",
      component: Vdialog,
      name: "Vdialog"
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
