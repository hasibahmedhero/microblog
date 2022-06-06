<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PostsController extends Controller {
	
	public function createPost(Request $request) {
		
		$title = $request->get('title');
		$content = $request->get('content');
		
		$new_post_id = DB::table('tbl_posts')->insertGetId([
			'user_id' => $request->user()->user_id,
			'title' => $title,
			'content' => $content,
			'created_at' => date('Y-m-d H:i:s')
		]);
		
		return json_encode([
			'success' => true,
			'new_post_id' => $new_post_id
		]);
	
	}
	
	public function getPosts(Request $request) {
		$offset = intval($request->get('offset'));
		if ($offset < 0) $offset = 0;
		
		$posts = DB::select("
			select tbl_posts.post_id, tbl_posts.title, tbl_posts.content, tbl_posts.created_at, tbl_users.name as author, sum(tbl_votes.vote) as vote from tbl_posts 
			left join tbl_users on tbl_posts.user_id = tbl_users.user_id
			left join tbl_votes on tbl_posts.post_id = tbl_votes.post_id
			where tbl_posts.user_id = ?
			group by tbl_posts.post_id
			order by tbl_posts.created_at desc
			limit 10
			offset ?
		", [$request->user()->user_id, $offset]);
		
		// if (!(isset($posts) && count($posts) > 0)) return json_encode(['success' => false, 'error_message' => 'No Post Found!']);
		
		foreach ($posts as $item) {
			$item->created_at = date('D, dS M Y  h:i A', strtotime($item->created_at));
			$item->content = substr($item->content, 0, 90);
			if (is_null($item->vote)) $item->vote = 0;
		}
		
		return json_encode([
			'success' => true,
			'posts' => $posts
		]);
	
	}
	
	public function deletePost(Request $request) {
		$post_id = intval($request->get('postId'));
		
		$affected = DB::table('tbl_posts')->where('post_id', $post_id)->where('user_id', $request->user()->user_id)->delete();
		
		if ($affected > 0) {
			DB::table('tbl_comments')->where('post_id', $post_id)->delete();
			DB::table('tbl_votes')->where('post_id', $post_id)->delete();
			
			return json_encode(['success' => true]);
		}
		
		return json_encode(['success' => false, 'error_message' => 'Post not found!']);
	
	}
	
	
	public function postComment(Request $request) {
		$post_id = intval($request->get('postId'));
		$comment = $request->get('comment');
		
		$check_post = DB::select("select * from tbl_posts where post_id = ?", [$post_id]);
		
		if (!(isset($check_post) && count($check_post) > 0)) return json_encode(['success' => false, 'error_message' => 'Post not found!!']);
		
		DB::table('tbl_comments')->insert([
			'post_id' => $post_id,
			'user_id' => $request->user()->user_id,
			'comment' => $comment,
			'created_at' => date('Y-m-d H:i:s')
		]);
		
		$comments = DB::select("
			select tbl_comments.comment_id, tbl_comments.comment, tbl_comments.created_at, tbl_users.name as author from tbl_comments
			left join tbl_users on tbl_comments.user_id = tbl_users.user_id
			where tbl_comments.post_id = ?
		", [$post_id]);
		
		foreach ($comments as $item) {
			$item->created_at = date('D, dS M Y  h:i A', strtotime($item->created_at));
		}
		
		return json_encode(['success' => true, 'comments' => $comments]);
	
	}
	
	public function postVote(Request $request) {
		$post_id = intval($request->get('postId'));
		$vote = intval($request->get('vote'));
		
		if ($vote > 0) {
			$vote = 1;
			
		} else if ($vote < 0) {
			$vote = -1;
			
		} else {
			return json_encode(['success' => false, 'error_message' => 'Something went wrong, please reload and try again!']);
		}
		
		
		$check_post = DB::select("select * from tbl_posts where post_id = ?", [$post_id]);
		if (!(isset($check_post) && count($check_post) > 0)) return json_encode(['success' => false, 'error_message' => 'Post not found!!']);
		
		
		$check_past_vote = DB::select("select * from tbl_votes where post_id = ? and user_id = ?", [$post_id, $request->user()->user_id]);
		
		if (isset($check_past_vote) && count($check_past_vote) > 0) {
			DB::table('tbl_votes')->where('vote_id', $check_past_vote[0]->vote_id)->update([
				'vote' => $vote
			]);
			
		} else {
			DB::table('tbl_votes')->insert([
				'post_id' => $post_id,
				'user_id' => $request->user()->user_id,
				'vote' => $vote,
				'created_at' => date('Y-m-d H:i:s')
			]);
		}
		
		// vote complete
		// grab new vote data
		
		$votes = DB::select("select sum(vote) as vote from tbl_votes where post_id = ?", [$post_id]);
		if (isset($votes) && count($votes) > 0) {
			$votes = intval($votes[0]->vote);
		} else {
			$votes = 0;
		}
		
		return json_encode(['success' => true, 'votes' => $votes, 'my_vote' => $vote]);
	
	}
	
	
}



