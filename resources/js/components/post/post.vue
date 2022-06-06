<template>
	<div>
		<div class="page-box">
			<div v-if="post" class="post-view">
				<div>
					<div><svg @click="voteClick(1)" :fill="myVote > 0 ? '#5EBA7D' : '#444'" style="cursor:pointer;" width="36" height="36" viewBox="0 0 36 36"><path d="M2 25h32L18 9 2 25Z"></path></svg></div>
					<div style="font-size:25px;" :style="{color:(votes < 0 ? '#f00' : '#444')}">{{votes.toString()}}</div>
					<div><svg @click="voteClick(-1)" :fill="myVote < 0 ? '#f00' : '#444'" style="cursor:pointer;" width="36" height="36" viewBox="0 0 36 36"><path d="M2 11h32L18 27 2 11Z"></path></svg></div>
				</div>
				<div>
					<h3>{{post.title}}</h3>
					<p class="post-meta">Posted on <i>{{post.created_at}}</i> by <i>{{post.author}}</i></p>
					<div>{{post.content}}</div>
				</div>
			</div>
			
			<div v-if="!post && !postError" style="width:100%; padding:50px 0; text-align:center;">
				<div class="spinner-border text-warning" role="status"></div>
			</div>
			
			<div v-if="postError">
				<b>{{postError}}</b>
			</div>
		</div>
		
		<div class="page-box" v-if="post">
			<h5>{{comments.length}} Comments</h5>
			<hr>
			<div v-if="comments.length">
				<div v-for="comment in comments">
					<p>{{comment.comment}}</p>
					<p class="comment-meta">Commented on <i>{{comment.created_at}}</i> by <i>{{comment.author}}</i></p>
					<hr>
				</div>
			</div>
			<div v-else>
				<p>No Comment yet.</p>
			</div>
		</div>
		
		<div class="page-box" v-if="post && $store.state.user">
			<h5>Write a Comment</h5>
			<div class="form-group">
				<textarea v-model="commentModel" class="form-control" placeholder="Your comment..."></textarea>
			</div>
			<div class="form-group">
				<button @click="commentSubmitClick" class="btn btn-primary">Post Comment</button>
			</div>
		</div>
		<div class="page-box" v-if="post && !$store.state.user">
			<router-link to="/login" style="color:#444;">Login to post a comment.</router-link>
		</div>
		
	</div>
</template>

<script>
	export default {
		data() {
			return {
				post: null,
				postError: false,
				comments: [],
				commentModel: '',
				myVote: 0,
				votes: 0
			}
		},
		watch: {
			$route(to, from) {
				if (to.matched[0].path == '/view-post/:postId' && from.matched[0].path == '/view-post/:postId') {
					this.loadPost();
				}
			}
		},
		methods: {
			loadPost() {
				this.post = null;
				this.postError = false;
				axios.post('/api/get-post-details/' + this.$route.params.postId).then((res) => {
					if (res.data.success) {
						this.post = res.data.post;
						this.comments = res.data.comments;
						this.myVote = res.data.my_vote;
						this.votes = res.data.votes;
					} else {
						this.postError = res.data.error_message;
					}
				}).catch((e) => {
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
				});
			},
			commentSubmitClick() {
				axios.post('/api/my-account/post-comment/', {postId:this.post.post_id, comment:this.commentModel}).then((res) => {
					if (res.data.success) {
						this.$toastr.s("Comment posted successfully.");
						this.comments = res.data.comments;
						this.commentModel = '';
					} else {
						this.$toastr.e(res.data.error_message);
					}
				}).catch((e) => {
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
				});
			},
			voteClick(vote) {
				if (!this.$store.state.user) {
					this.$toastr.i('Please login to vote.');
					return;
				}
				
				if (parseInt(vote) === parseInt(this.myVote)) return;
				
				axios.post('/api/my-account/post-vote/', {postId:this.post.post_id, vote:vote}).then((res) => {
					if (res.data.success) {
						this.$toastr.s("Your vote saved successfully!!");
						this.votes = res.data.votes;
						this.myVote = res.data.my_vote;
					} else {
						this.$toastr.e(res.data.error_message);
					}
				}).catch((e) => {
					this.$toastr.e(e.response && e.response.status ? 'Something went wrong, please try again later.' : 'No internet connection!!');
				});
			}
		},
		mounted() {
			this.loadPost();
		}
	}
</script>