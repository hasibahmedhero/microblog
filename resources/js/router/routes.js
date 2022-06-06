import Vue from 'vue';
import VueRouter from 'vue-router';

import notFound from '../components/notFound.vue';
import homepage from '../components/homepage/homepage.vue';
import post from '../components/post/post.vue';

import login from '../components/auth/login.vue';

import myaccountDashboard from '../components/myaccount/dashboard.vue';
import createPost from '../components/myaccount/createpost.vue';

Vue.use(VueRouter);

const routes = new VueRouter({
	mode: 'history',
	routes: [
		{path: '*', component: notFound, meta: {title: '404 Not Found'}},
		{path: '/', component: homepage},
		{path: '/view-post/:postId', component: post},
		
		{path: '/login', component: login, meta: {title: 'Login'}},

		{path: '/my-account/dashboard', component: myaccountDashboard, meta: {requiresAuth:true}},
		{path: '/my-account/create-post', component: createPost, meta: {requiresAuth:true}},
		
	]
});

import store from '../store/store';
routes.beforeEach(function(to, from, next) {
	if (to.meta && to.meta.title) {
		window.document.title = to.meta.title + ' | Micro Blog';
		
	} else {
		window.document.title = 'Micro Blog';
	}
	
	if (to.meta.requiresAuth) {
		if (store.state.user) next();
		else next({ path: '/login'});
	} else {
		next();
	}
});

export default routes;
