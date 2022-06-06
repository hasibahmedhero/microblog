<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class PostsController extends Controller {
	
	
	public function getPosts(Request $request) {
		$offset = intval($request->get('offset'));
		if ($offset < 0) $offset = 0;
		
		$posts = DB::select("
			select tbl_posts.post_id, tbl_posts.title, tbl_posts.content, tbl_posts.created_at, tbl_users.name as author, sum(tbl_votes.vote) as vote from tbl_posts 
			left join tbl_users on tbl_posts.user_id = tbl_users.user_id
			left join tbl_votes on tbl_posts.post_id = tbl_votes.post_id
			group by tbl_posts.post_id
			order by tbl_posts.created_at desc
			limit 10
			offset ?
		", [$offset]);
		
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
	
	
	public function getPostDetails(Request $request, $postId) {
		
		$post = DB::select("
			select tbl_posts.post_id, tbl_posts.title, tbl_posts.content, tbl_posts.created_at, tbl_users.name as author from tbl_posts
			left join tbl_users on tbl_posts.user_id = tbl_posts.user_id
			where tbl_posts.post_id = ?
		", [$postId]);
		
		if (!(isset($post) && count($post) > 0)) return json_encode(['success' => false, 'error_message' => 'Post not found!']);
		$post[0]->created_at = date('D, dS M Y  h:i A', strtotime($post[0]->created_at));
		
		$comments = DB::select("
			select tbl_comments.comment_id, tbl_comments.comment, tbl_comments.created_at, tbl_users.name as author from tbl_comments
			left join tbl_users on tbl_comments.user_id = tbl_users.user_id
			where tbl_comments.post_id = ?
		", [$postId]);
		
		foreach ($comments as $item) {
			$item->created_at = date('D, dS M Y  h:i A', strtotime($item->created_at));
		}
		
		$votes = DB::select("select sum(vote) as vote from tbl_votes where post_id = ?", [$postId]);
		if (isset($votes) && count($votes) > 0) {
			$votes = intval($votes[0]->vote);
		} else {
			$votes = 0;
		}
		
		$my_vote = 0;
		if (Auth::check()) {
			$result = DB::select("select vote from tbl_votes where post_id = ? and user_id = ?", [$postId, Auth::user()->user_id]);
			if (isset($result) && count($result) > 0) {
				$my_vote = intval($result[0]->vote);
			}
		}
		
		return json_encode([
			'success' => true,
			'post' => $post[0],
			'comments' => $comments,
			'votes' => $votes,
			'my_vote' => $my_vote
		]);
	}
	
}



