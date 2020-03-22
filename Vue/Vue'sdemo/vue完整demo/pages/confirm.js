import vm from "../main";
export function confirmDialog({ title, text, cancelText, confirmText }) {
  return new Promise((resolve, reject) => {
    vm.$emit("setDialog", {
      title,
      text,
      cancelText,
      confirmText,
      resolve,
      reject
    });
  });
}
export default confirmDialog;