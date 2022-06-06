<?php

use Illuminate\Support\Facades\Route;

use Auth;
use App\pp\user;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/{any}', function () {
	$user_data = null;
	if (Auth::check()) {
		$user = new user;
		$get_user_details = $user->createOrGetUser(Auth::user()->mobile_no);
		if ($get_user_details['success'] == true) $user_data = $get_user_details['data'];
	}
    return view('app', ['user' => $user_data]);
})->where('any', '^(?!api).*$'); //->where('any', '.*');
