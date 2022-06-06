<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use App\pp\otp;
use App\pp\user;

class AuthController extends Controller {
	
	public function requestOtp(Request $request) {
		$mobile_no = $request->get('mobile_no');
		$otp = new otp;
		return json_encode($otp->requestOtp($mobile_no));
	}
	
	public function verify(Request $request) {
		$otp = new otp;
		
		$mobile_no = $otp->getValidMobileNo($request->get('mobile_no'));
		$user_provided_otp = $request->get('otp');
		
		$otp_verify_status = $otp->verifyOtp($mobile_no, $user_provided_otp);
		if ($otp_verify_status['success'] != true) return json_encode(['success' => false, 'error_message' => 'otp verification failed...']);
		
		$user = new user;
		$get_user_details = $user->createOrGetUser($mobile_no);
		if ($get_user_details['success'] != true) return json_encode(['success' => false, 'error_message' => $get_user_details['error_message']]);
		
		Auth::loginUsingId($get_user_details['data']['user_id']);
		return json_encode(['success' => true, 'user' => $get_user_details['data']]);
	}
	
	
	
	public function logout(Request $request) {
		Auth::logout();
		return json_encode(['success' => true]);
	}
}
