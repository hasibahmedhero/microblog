<?php
namespace App\pp;

use DB;
class otp {
	
	public function requestOtp($mobile_no) {
		
		$mobile_no = $this->getValidMobileNo($mobile_no);
		if ($mobile_no == 0) return ['success' => false, 'error_message' => 'invalid_number'];
		
		
		$request_count = DB::select("select * from tbl_otp_requests where mobile_no = ? and created_at > ?", [$mobile_no, date('Y-m-d H:i:s', strtotime('-5 minutes'))]);
		
		if (isset($request_count) && count($request_count) >= 3) return ['success' => false, 'error_message' => 'too_many_requests'];
		
		$otp = rand(10000, 99999);
		$data = [
			'mobile_no' => $mobile_no,
			'otp' => $otp,
			'created_at' => date('Y-m-d H:i:s'),
			'status' => 0
		];
		
		DB::table('tbl_otp_requests')->insert($data);
		
		
		$sms = new sms;
		$smsResult = $sms->SendInstantSMS($mobile_no, 'Your OTP is: ' . $otp . ' Validity 5 minutes');
		
		return ['success' => true];
	}
	
	
	
	
	public function verifyOtp($mobile_no, $user_provided_otp) {
		
		$mobile_no = $this->getValidMobileNo($mobile_no);
		if ($mobile_no == 0) return ['success' => false, 'error_message' => 'invalid_number'];
		
		$find_otp = DB::select("select otp from tbl_otp_requests where mobile_no = ? and created_at > ? order by created_at desc limit 1", [$mobile_no, date('Y-m-d H:i:s', strtotime('-3 minutes'))]);
		
		if (!(isset($find_otp) && count($find_otp) > 0)) return ['success' => false, 'error_message' => 'otp_not_found_or_timeout'];
		
		$found_otp = $find_otp[0]->otp;
		
		if ($found_otp != $user_provided_otp) return ['success' => false, 'error_message' => 'wrong_otp'];
		
		return ['success' => true];
		
	}
	
	

	
	
	
	public function getValidMobileNo($mobile_no) {
		$mobile_no = intval($mobile_no);
		$mobile_no = (string) $mobile_no;
		
		if (substr($mobile_no, 0, 1) == "1" && strlen($mobile_no) == 10) {
			return intval("880" . $mobile_no);
			
		} else if (strlen($mobile_no) == 13 && substr($mobile_no, 0, 4) == "8801") {
			return intval($mobile_no);
			
		} else {
			return 0;
		}
	}
	
	
	
	
}
