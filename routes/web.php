<?php

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
Route::group(['middleware' => 'preventBackHistory'],function(){
	Route::get('/','Admin\Auth\LoginController@showLoginForm')->name('admin.showLoginForm');
	Route::get('admin/login','Admin\Auth\LoginController@showLoginForm')->name('admin.login');
	Route::post('admin/login', 'Admin\Auth\LoginController@login');
	Route::get('admin/resetPassword','Admin\Auth\PasswordResetController@showPasswordRest')->name('admin.resetPassword');
	Route::post('admin/sendResetLinkEmail', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.sendResetLinkEmail');
	Route::get('admin/find/{token}', 'Admin\Auth\PasswordResetController@find')->name('admin.find');
	Route::post('admin/create', 'Admin\Auth\PasswordResetController@create')->name('admin.sendLinkToUser');
	Route::post('admin/reset', 'Admin\Auth\PasswordResetController@reset')->name('admin.resetPassword_set');
	Route::group(['prefix' => 'admin','middleware'=>'Admin','namespace' => 'Admin','as' => 'admin.'],function(){
		Route::get('/dashboard','MainController@dashboard')->name('dashboard');
		Route::get('/logout','Auth\LoginController@logout')->name('logout');
		
		//====================> User Management =========================
		Route::get('/user','UsersController@index')->name('index');
		Route::get('/user/show','UsersController@show')->name('user.show');
		Route::get('/user/create','UsersController@create')->name('create');
		Route::post('/user/store','UsersController@store')->name('store');
		Route::get('/user/edit/{id}','UsersController@edit')->name('edit');
		Route::post('/user/update/{id}','UsersController@update')->name('update');
		Route::post('/user/delete/{id}','UsersController@delete')->name('delete');
		Route::post('/user/change_status','UsersController@change_status')->name('change_status');
		//====================> Update Admin Profile =========================
		Route::get('/profile','UsersController@updateProfile')->name('profile');
		Route::post('/updateProfileDetail','UsersController@updateProfileDetail')->name('updateProfileDetail');
		Route::post('/updatePassword','UsersController@updatePassword')->name('updatePassword');
		//====================> CMS Management =========================
		Route::get('/cms','CmsController@index')->name('cms.index');
		Route::get('/cms/show','CmsController@show')->name('cms.show');
		Route::get('/cms/edit/{id}','CmsController@edit')->name('cms.edit');
		Route::post('/cms/update/{id}','CmsController@update')->name('cms.update');
		Route::post('/cms/delete/{id}','CmsController@delete')->name('cms.delete');
		Route::post('/cms/change_status','CmsController@change_status')->name('cms.change_status');
	});
});

Event::listen('send-notification-assigned-user', function($value,$data) {
    try {

        $path = public_path().'/webservice_logs/'.date("d-m-Y").'_notification.log';
        file_put_contents($path, "\n\n".date("d-m-Y") . "_ : ".json_encode(['user'=>$value->id,'data'=>$data])."\n", FILE_APPEND);
        $response = [];
        $device_token = $value->device_token;
        if($value->device_type == 'ios'){
            try {
                $passphrase = '';
                $cert =config_path('iosCertificates/pushcert.pem');
                $message = json_encode($data);
                $ctx = stream_context_create();
                stream_context_set_option($ctx, 'ssl', 'local_cert', $cert);
                stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
                $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,
                    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                if (!$fp)
                    exit("Failed to connect: $err $errstr" . PHP_EOL);
                $body['aps'] = array('alert' => $data['message'],'sound' => 'default');
                $body['params'] = $data;
                $payload = json_encode($body);
                $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg));
                fclose($fp);
                $response[] = $result;
                file_put_contents($path, "\n\n".date("d-m-Y") . "_Response_IOS payload : ".json_encode($payload)."\n", FILE_APPEND);
                file_put_contents($path, "\n\n".date("d-m-Y") . "_Response_IOS : ".json_encode(@$_Responsese)."\n", FILE_APPEND);
            } catch (Exception $e) {
                file_put_contents($path, "\n\n".date("d-m-Y") . "_Response_IOS : ".$e."\n", FILE_APPEND);
            }
        }else{
            file_put_contents($path, "\n\n".date("d-m-Y") . "_Notification_data : ".json_encode($data)."\n", FILE_APPEND);

            $response[] = PushNotification::setService('fcm')->setMessage([
                'data' => $data
                ])->setApiKey('AAAAU9VW3iA:APA91bEAs1WIOkxiDDWTH9fqP40os8Z5UmROCx7Z6v4sBa-btKUic9_cFGMCBZGTRLLdJg2QIs37vFHXTXZDaiBgng7_UzREJmXdg2YeMzu20dHGsklkTakWUNAsszJEdheMo-p6VHyu')->setConfig(['dry_run' => false])->sendByTopic($data['type'])->setDevicesToken([$device_token])->send()->getFeedback();
            }
            file_put_contents($path, "\n\n".date("d-m-Y") . "_Response_User_android : ".json_encode($response)."\n", FILE_APPEND);
            return $response;
        } catch (Exception $e) {
            file_put_contents($path, "\n\n".date("d-m-Y") . "_Response : ".json_encode($e)."\n", FILE_APPEND);
        }
    });