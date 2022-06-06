import Vue from 'vue';
import routes from './router/routes';
import store from './store/store';

import VueToastr from 'vue-toastr';
Vue.use(VueToastr, {defaultProgressBar:false});

require('./bootstrap');

Vue.component('app-header', require('./components/layouts/header.vue').default);
Vue.component('app-footer', require('./components/layouts/footer.vue').default);

const app = new Vue({
    el: '#app',
	router: routes,
	store: store
});
