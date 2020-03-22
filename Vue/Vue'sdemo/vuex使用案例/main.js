// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'

import router from './router'
import {Dialog} from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import App from './App'
import api from './api.js';
import store from './store'

Vue.prototype.$http = api;
Vue.config.productionTip = false;
Vue.use(Dialog);
/* eslint-disable no-new */
export default new Vue({
  el: '#app',
  store,
  router,
  render: h => h(App),
})

