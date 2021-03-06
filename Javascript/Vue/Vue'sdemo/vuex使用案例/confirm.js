import dialogStore from "./components/ConfirmDialog/dialogStore.js"; //引入某个store对象
// 传入标题、内容、确认按钮和取消按钮的文案
export function confirmDialog({ title, text, cancelText, confirmText }) {
  return new Promise((resolve, reject) => {
    // 调用 dialogStore.commit 提交 setDialog
    // 把 reject 和 resolve 通过事件传参带过去，方便进行 Promise 状态扭转
    dialogStore.commit("setDialog", {title,text,cancelText,confirmText,resolve,reject});
  });
}
export default confirmDialog;

