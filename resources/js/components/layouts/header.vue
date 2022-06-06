<template>
	<header>
		<div>
			<div style="font-size:25px;"><router-link to="/" style="color:#444; text-decoration:none;">Micro Blog</router-link></div>
			<nav>
				<router-link to="/">Home</router-link>
				<router-link v-if="$store.state.user" to="/my-account/dashboard">Dashboard</router-link>
				<router-link v-if="$store.state.user" to="/my-account/create-post">Create Post</router-link>
				<router-link v-if="!$store.state.user" to="/login">Login</router-link>
				<a v-if="$store.state.user" @click.prevent="logout" href="#">Logout</a>
			</nav>
		</div>
	</header>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		methods: {
			logout() {
				axios.post('/api/auth/logout', this.form).then((res) => {
					this.$store.commit('setUser', null);
					this.$router.push({path: '/'});
				}).catch((e) => {
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
				});
			}
		}
	}
</script>