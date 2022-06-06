<?php
namespace App\pp;

use DB;


class user {
	
	public function createOrGetUser($mobile_no) {
		
		$find_user = DB::select("select user_id, mobile_no, name, email, status from tbl_users where mobile_no = ?", [$mobile_no]);
		
		if (isset($find_user) && count($find_user) > 0) {
			
			if ($find_user[0]->status != 1) {
				return ['success' => false, 'error_message' => 'Sorry!! Your account has been disabled!!'];
				
			} else {
				return ['success' => true, 'data' => ((array) $find_user[0])];
			}
			
		} else {
			$new_id = DB::table('tbl_users')->insertGetId([
				'mobile_no' => $mobile_no,
				'name' => $mobile_no,
				'created_at' => date('Y-m-d H:i:s'),
				'status' => 1
			]);
			
			$new_user = DB::select("select user_id, mobile_no, name, email, status from tbl_users where user_id = ?", [$new_id]);
			
			return ['success' => true, 'data' => ((array) $new_user[0])];
		}
		
	}
	
	
}
