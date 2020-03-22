import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

const state = {
  // 弹窗列表，用来保存可能弹窗的一系列弹窗
  dialogList: []
};
const mutations = {
  removeDialog(state, index) {
    // 移除弹窗
    state.dialogList.splice(index, 1);
  },
  setDialog(state, { title, text, cancelText, confirmText, reject,resolve }) {
    // 追加弹窗
    state.dialogList.push({
      title,
      text,
      cancelText,
      confirmText,
      reject,
      resolve,
    });
  }
};
const dialogStore = new Vuex.Store({
  state,
  mutations
});
export default dialogStore;
