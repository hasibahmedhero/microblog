<template>
	<div>
		<div v-if="$store.state.user" style="width:320px; margin-top:100px; margin-left:auto; margin-right:auto;">
			<p>you are already logged in with - {{$store.state.user.mobile_no}}</p>
			<p style="text-align:center;"><a class="btn" @click.prevent="logout" href="#">Login to a different account.</a></p>
		</div>
		<otp v-else @loginSuccess="onLoginSuccess"/>
		<p style="margin-top:80px; text-align:center"><router-link to="/">Back To Home</router-link></p>
	</div>
</template>

<script>
	import otp from "./otp";
	export default {
		components: {otp},
		data() {
			return {
				
			}
		},
		methods: {
			onLoginSuccess() {
				this.$router.push('/my-account/dashboard');
			},
			logout() {
				axios.post('/api/auth/logout', this.form).then((res) => {
					this.$store.commit('setUser', null);
				}).catch((e) => {
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
				});
			}
		}
	}
</script>
