import Vue from 'vue';
import App from './App.vue';
window._ = require('lodash');
window.$ = window.jQuery = require('jquery');

Vue.config.productionTip = false;

new Vue({
  render: (h) => h(App),
}).$mount('#app');
