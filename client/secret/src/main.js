import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import ApiHttpClient from './api/http'

Vue.config.productionTip = false;
Vue.prototype.$ApiHttpClient = ApiHttpClient;

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
