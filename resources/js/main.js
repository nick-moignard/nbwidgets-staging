import Vue from "vue";
import "./plugins/vuetify";
import router from "./router";

import BootstrapVue from "bootstrap-vue";
import store from "./store";
import App from "./App";

import Default from "./Dashboard/baseLayout.vue";
import VueHighlightJS from 'vue-highlightjs'

import * as Sentry from '@sentry/browser';
import { Vue as VueIntegration } from '@sentry/integrations';

// Tell Vue.js to use vue-highlightjs
Vue.use(VueHighlightJS)
Vue.config.productionTip = false;

Vue.use(BootstrapVue);

Vue.component("default-layout", Default);

Sentry.init({
    dsn: 'https://c879329a48764d1484fdb458015f0715@o404846.ingest.sentry.io/5271087',
    integrations: [new VueIntegration({Vue, attachProps: true})],
});

new Vue({
  el: "#app",
  router,
  store,
  template: "<App/>",
  components: { App },
});
