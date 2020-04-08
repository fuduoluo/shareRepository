<!-- ConfirmDialog.vue  弹窗组件-->
<template>
  <!-- 强制出现 display: block -->
  <div class="modal" tabindex="-1" role="dialog" style="display: block">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <!-- 弹窗标题 -->
          <h4 class="modal-title">{{ dialogInfo.title || "提示" }}</h4>
        </div>
        <div class="modal-body">
          <!-- 弹窗内容 -->
          <p>{{ dialogInfo.text }}</p>
        </div>
        <div class="modal-footer">
          <!-- 取消按钮，点击取消，cancelText 可设置按钮文案 -->
          <button type="button" class="btn btn-default" @click="cancel()">
            {{ dialogInfo.cancelText || "取消" }}
          </button>
          <!-- 确认按钮，点击确认，confirmText 可设置按钮文案 -->
          <button type="button" class="btn btn-primary" @click="confirm()">
            {{ dialogInfo.confirmText || "确认" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import vm from "../main";
import dialogStore from "./dialogStore";

export default {
  props: {
    // 弹窗相关信息
    dialogInfo: {
      type: Object,
      default: () => {}
    },
    index: {
      type: Number
    }
  },
  methods: {
    // 点击取消
    cancel() {
      // 要先判断下 reject 方法在不在
      if(this.dialogInfo.reject){
        // 确认就 reject 掉
        this.dialogInfo.reject()
        // 移除掉这个弹窗
        dialogStore.commit("removeDialog", this.index)
      }
    },
    // 点击确认
    confirm() {
      // 要先判断下 resolve 方法在不在
      if(this.dialogInfo.resolve){
        // 确认就 resolve 掉
        this.dialogInfo.resolve()
        // 移除掉这个弹窗
        dialogStore.commit("removeDialog", this.index)
      }
    }
  }
};
</script>