<template>
  <div class="details">
    <myComponent
      :show.sync="valueChild"
      style="padding: 30px 20px 30px 5px;border:1px solid #ddd;margin-bottom: 10px;"
    ></myComponent>
    <button @click="changeValue">toggle</button>
  </div>
</template>
<script>
import Vue from "vue";
Vue.component("myComponent", {
  template: `<div v-if="show">
                    <p>默认初始值是{{show}}，所以是显示的</p>
                    <button @click.stop="closeDiv">关闭</button>
                 </div>`,
  props: ["show"],
  methods: {
    closeDiv() {
      this.$emit("update:show", false); //触发 input 事件，并传入新值
    }
  }
});
/**
 * 在有些情况下，我们可能需要对一个 prop 进行“双向绑定”不幸的是，
 * 真正的双向绑定会带来维护上的问题，因为子组件可以修改父组件，且在父组件和子组件都没有明显的改动来源。
 * 推荐以 update:myPropName 的模式触发事件取而代之
 * 当我们用一个对象同时设置多个 prop 的时候，也可以将这个 .sync 修饰符和 v-bind 配合使用
 * 把 doc 对象中的每一个属性 (如 title) 都作为一个独立的 prop 传进去，然后各自添加用于更新的 v-on 监听器。
 * <text-document :myPropName.sync="doc"></text-document>
 *  
 * 功能：vue 修饰符sync的功能是：当一个子组件改变了一个 prop 的值时，这个变化也会同步到父组件中所绑定
 */
export default {
  data() {
    return {
      valueChild: true
    };
  },
  methods: {
    changeValue() {
      this.valueChild = !this.valueChild;
    }
  }
};
</script>
