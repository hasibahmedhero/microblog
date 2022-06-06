<template>
	<div class="page-box">
		<h5>Create New Post</h5>
		
		<div class="form-group">
			<input v-model="titleModel" class="form-control" type="text" placeholder="Post Title">
		</div>
		<div class="form-group">
			<textarea v-model="contentModel" class="form-control" placeholder="Post Content" style="min-height:200px;"></textarea>
		</div>
		<div class="form-group">
			<button @click="submitClick" class="btn btn-primary">Save Post</button>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				titleModel: '',
				contentModel: '',
			}
		},
		methods: {
			submitClick() {
				axios.post('/api/my-account/create-post/', {title:this.titleModel, content:this.contentModel}).then((res) => {
					if (res.data.success) {
						this.$toastr.s("Post created successfully.");
						this.$router.push('/view-post/' + res.data.new_post_id);
						
					} else {
						this.$toastr.e(res.data.error_message);
					}
				}).catch((e) => {
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
				});
			}
		}
	}
</script>
