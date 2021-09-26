import Vue from "vue";
import App from "./App.vue";

//Hivatalos alapértelmezett import
import router from "./router";
//A defaulton kívül, még be lehet emelni, egy ott lévő változót, vagy metódust és aliasolni azt a használathoz.
//import router, {defaultPageNumber as DEFAULT_PAGE_NUMBER} from "./router";
import {SecretClient} from "./api";

//console.log(DEFAULT_PAGE_NUMBER);

Vue.config.productionTip = false;
Vue.prototype.$GetSecret = SecretClient;

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
