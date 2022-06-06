<?php
namespace App\pp;

use DB;
class sms {
	
	public function SendInstantSMS($number, $message) {
		return ['success' => false, 'error_message' => 'Failed to send SMS'];
		
		try {
			$url = "XXXXXXXXXXXXXXXXXXX";
			$data = [
				"api_key" => "XXXXXXXXXXXX",
				"type" => "text",
				"contacts" => $number,
				"senderid" => "XXXXXXXXXX",
				"msg" => $message,
			];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			curl_close($ch);
			
			if (strpos($response, 'SMS SUBMITTED') !== false) {
				return ['success' => true];
				
			} else {
				return ['success' => false, 'error_message' => $response];
			}
			
		} catch(Exception $e) {
			return ['success' => false, 'error_message' => $e->getMessage()];
		}
		
	}
	
	
}
