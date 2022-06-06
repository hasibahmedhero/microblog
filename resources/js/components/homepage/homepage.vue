<template>
	<div class="page-box">
		<h5>Post Feed</h5>
		<hr>
		<div v-if="posts.length">
			<div v-for="post in posts">
				<router-link :to="'/view-post/'+post.post_id" style="color:#444; text-decoration:none;">
					<div class="post-item-body">
						<div class="post-item-vote">
							<span :style="{background:(post.vote < 0 ? '#fff8f8' : '#f8f8f8')}">{{post.vote}}</span><span>Votes</span>
						</div>
						<div>
							<h5>{{post.title}}</h5>
							<p class="post-item-meta">Posted on <i>{{post.created_at}}</i> by <i>{{post.author}}</i></p>
							<div>{{post.content}}...</div>
						</div>
					</div>
				</router-link>
				<hr>
			</div>
		</div>
		<button v-if="!allLoaded && posts.length > 0" @click="loadMoreClick" class="btn btn-success" :class="{disabled:loading}">Load More</button>
		
		<div v-if="!posts.length" style="width:100%; padding:50px 0; text-align:center;">
			<div class="spinner-border text-warning" role="status"></div>
		</div>
		
	</div>
</template>

<script>
	export default {
		data() {
			return {
				posts: [],
				loading: false,
				allLoaded: false,
			}
		},
		methods: {
			loadPosts() {
				if (this.loading) return;
				this.loading = true;
				axios.post('/api/get-posts/', {offset:this.posts.length}).then((res) => {
					if (res.data.success) {
						this.posts = this.posts.concat(res.data.posts);
						if (res.data.posts.length < 10) this.allLoaded = true;
					} else {
						this.$toastr.e(res.data.error_message);
					}
					this.loading = false;
				}).catch((e) => {
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
					this.loading = false;
				});
			},
			loadMoreClick() {
				this.loadPosts();
			}
		},
		mounted() {
			this.loadPosts();
		}
	}
</script>
