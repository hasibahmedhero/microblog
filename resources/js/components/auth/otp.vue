<template>
	<div style="max-width:520px; background:#fff; margin:100px auto 0 auto; padding:40px; box-shadow:0 2px 2.5px 0 rgb(0 0 0 / 9%);">
		<div v-if="step == 'request'">
			<p style="color:#888;">A verification code will be sent to your number.</p>
			<div style="display:flex; align-items:center; column-gap:10px; border-bottom:1px solid #ccc;">
				<span style="background:transparent">+880</span>
				<input v-model="numberModel" class="pp-hide-number-arrow" type="number" placeholder="Mobile Number" style="border:none !important; outline:none !important; width:100%; background:transparent;">
			</div>
			<button @click="requestClick" class="btn btn-success" :class="{disabled:isLoading}" style="width:100%; text-align:center; margin-top:10px;">Proceed</button>
		</div>
		<div v-if="step == 'verify'">
			<p style="color:#888;">Please enter the code sent to your mobile.</p>
			<div style="display:flex; align-items:center; column-gap:10px; border-bottom:1px solid #ccc;">
				<input ref="verificationCode" class="pp-hide-number-arrow" type="number" placeholder="Verification Code" style="border:none !important; outline:none !important; width:100%; background:transparent; padding-left:0;">
			</div>
			<button @click="verifyClick" class="btn btn-success" :class="{disabled:isLoading}" style="width:100%; text-align:center; margin-top:10px;">Verify</button>
		</div>
		<div v-if="step == 'failed'">
			<p style="color:red; text-align:center;">OTP verification failed!</p>
			<button @click="step = 'request'" class="btn btn-secondary" style="width:100%; text-align:center; margin-top:10px;">Try Again</button>
		</div>
		<div v-if="step == 'success'">
			<div style="color:#388e3c;">Verification successful!</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				isLoading: false,
				step: 'request',
				numberModel: null
			}
		},
		methods: {
			requestClick() {
				if (this.isLoading) return;
				this.isLoading = true;
				axios.post('/api/auth/requestOtp', {mobile_no: this.numberModel}).then((res) => {
					this.isLoading = false;
					if (res.data.success) {
						this.step = 'verify';
						setTimeout(() => {
							this.$refs.verificationCode.focus();
						}, 50);
					} else {
						this.$toastr.e(res.data.error_message);
					}
				}).catch((e) => {
					this.isLoading = false;
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
				});
			},
			verifyClick() {
				if (this.isLoading) return;
				
				const otp = this.$refs.verificationCode.value;
				if (!otp) {
					this.$toastr.e('Please enter the code sent to your mobile.');
					return;
				}
				
				this.isLoading = true;
				axios.post('/api/auth/verify', {mobile_no: this.numberModel, otp:otp}).then((res) => {
					this.isLoading = false;
					if (res.data.success) {
						this.step = 'success';
						this.$store.commit('setUser', res.data.user);
						this.$emit('loginSuccess');
					} else {
						this.$toastr.e(res.data.error_message);
						this.step = 'failed';
					}
				}).catch((e) => {
					this.isLoading = false;
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
					this.step = 'failed';
				});
			}
		}
	}
</script>
