import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import GetSecret from './secret/client'

Vue.config.productionTip = false;
Vue.prototype.$GetSecret = GetSecret;

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
