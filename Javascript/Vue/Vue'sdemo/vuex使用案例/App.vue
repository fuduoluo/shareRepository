<template>
  <div id="app">
    <!-- <img src="./assets/logo.png" /> -->
    <router-view></router-view>
    <!-- 动态组件由 vm 实例的 component 控制 -->
    <component
      v-for="(dialogInfo, index) in dialogList"
      :key="index"
      :is="ConfirmDialog"
      :dialogInfo="dialogInfo"
      :index="index"
    ></component>
  </div>
</template>

<script>
// 弹窗组件
import ConfirmDialog from "./components/ConfirmDialog/ConfirmDialog.vue";
import dialogStore from "./components/ConfirmDialog/dialogStore";
export default {
  name: "app",
  computed: {
    dialogList() {
      // 绑定 dialogStore 的 dialogList 一系列弹窗列表
      return dialogStore.state.dialogList;
    }
  },
  data() {
    return {
      // 用户绑定组件
      ConfirmDialog
    };
  }
};
</script>
<style scoped>
@import "https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css";
</style>
