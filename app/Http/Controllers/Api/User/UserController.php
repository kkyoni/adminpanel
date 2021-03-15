<?php

namespace App\Http\Controllers\Api\User;
use App\Jobs\sendNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Helpers\GlobalH;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Otp;
use App\Helpers\Helper;
use App\Models\Cms;
use Event;
use PushNotification;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class UserController extends Controller{
    public function __construct(){}
    public function getAuthenticatedUser(){
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }
    
    /* -----------------------------------------------------------------------------------------
    @Description: Function for Login
    -------------------------------------------------------------------------------------------- */

    public function login(Request $request){
        if($request->social_media && $request->social_id){

            $validation_array = [
                'social_media'      => 'required',
                'social_id'         => 'required',
                // 'device_type'       => 'required',
                // 'device_token'      => 'required',
            ];
        }else{
            $validation_array = [
                'email'         => 'required',
                'password'      => 'required|min:6',
                'device_type'   => 'required',
                'device_token'  => 'required',
            ];
        }
        $validation = Validator::make($request->all(),$validation_array);
        if($validation->fails()){
            return response()->json(['status' => 'error','message' => $validation->messages()->first()],200);
        }
        try{
            if($request->social_media && $request->social_id){
                // dd("in");
                $user = User::where(['social_id'=>request('social_id')])->first();
                if(empty($user)){

                $user = User::firstOrCreate([                    
                    "email"    =>request('email')

                ]);

                $joindiamond = Setting::where('code','joindiamond')->first();

                $user->username = request('username');
                $user->social_id = request('social_id');
                $user->social_media = request('social_media');
                $user['diamond'] = $joindiamond->value;
                if($user->is_verify == '0'){
                    $token = mt_rand(1000, 9999);
                    $user->otp = $token;
                }
                try {
                    if($request->avatar){
                        $filename = Str::random(10).'.jpg';
                        file_put_contents(storage_path().'/app/public/avatar/' . $filename, file_get_contents($request->avatar));
                        $user->avatar = $filename;
                    } else {
                        $user->avatar = "default.png";
                    }
                }catch (\Intervention\Image\Exception\NotReadableException $e) {
                }
                
                $user->save();
                $token = JWTAuth::fromUser($user);
                $userdata = User::where('id',$user->id)->first();
                $userdata->device_token = $request->device_token;
                $userdata->device_type = $request->device_type;
                $userdata->status = 'active';
                $userdata->user_type = 'user';
                $userdata->otp_varifiy = '1';
                $userdata->save();
                $data['token']=$token;
                $data['user']=$userdata;
                
                return response()->json(['status' => 'success','message' => 'Login Successfully Done..!','data'=>$data], 200);
                } else {
                    $token = JWTAuth::fromUser($user);
                $userdata = User::where('id',$user->id)->first();
                $userdata->device_token = $request->device_token;
                $userdata->device_type = $request->device_type;
                $userdata->status = 'active';
                $userdata->user_type = 'user';
                $userdata->otp_varifiy = '1';
                $userdata->save();
                $data['token']=$token;
                $data['user']=$userdata;
                
                return response()->json(['status' => 'success','message' => 'Login Successfully Done..!','data'=>$data], 200);
                }
            }else
            {
                if(filter_var($request->email, FILTER_VALIDATE_EMAIL) ){
                    $credentials = $request->only('email', 'password','user_type');
                }else{
                    $credentials =[ 'contact_number'=>$request->email,'password'=>$request->password ,'user_type'=>$request->user_type];
                }
                $data= [];
                try {
                    if(! $token = JWTAuth::attempt($credentials)) {
                        return response()->json(['status' => 'error','message' => 'Please try again with Correct Details', 'data' => (object)[]], 200);
                    }
                    if(filter_var($request->email, FILTER_VALIDATE_EMAIL) ){
                        $userTypeCheck = User::where('email',$request->get('email'))->where('status','active')->first();
                    }else{
                        $userTypeCheck = User::where('contact_number',$request->get('email'))->where('status','active')->first();
                    }
                    if(!empty($userTypeCheck)){
                        if($userTypeCheck->user_type == 'user'){
                            if($userTypeCheck->status != 'active'){
                                return response()->json(['status' => 'error','message' => 'You are not able to login in this Application','data' => (object)[]], 200);
                            }
                        }
                    }else{
                        if(filter_var($request->email, FILTER_VALIDATE_EMAIL) ){
                            $userTypeCheck = User::where('email',$request->get('email'))->where('status','inactive')->first();
                        }else{
                            $userTypeCheck = User::where('contact_number',$request->get('email'))->where('status','inactive')->first();
                        }
                        $data['object'] = (object)[];
                        if(!empty($userTypeCheck->reason_for_inactive)){
                            $data['reason_for_inactive'] = $userTypeCheck->reason_for_inactive;
                        } else {
                            $data['reason_for_inactive'] = "You have Violated the our Policies so your Account.";
                        }
                        return response()->json(['status' => 'error','message' => 'You are not able to login this application because of '.$data['reason_for_inactive'],'data' => (object)[]], 200);
                    }
                }catch (JWTException $e) {
                    return response()->json(['status' => 'error','message' => 'could_not_create_token', 'data' => (object)[]], 200);
                }
                if($userTypeCheck->user_type == 'user'){
                    $data['token'] = $token;
                    $data['user'] = $userTypeCheck;
                    $userTypeCheck->device_token = $request->device_token;
                    $userTypeCheck->device_type = $request->device_type;
                    $userTypeCheck->save();

                    $otpNumber = random_int(1000, 9999);
                    //$otpNumber = (1234);
                    $checkContactNumInUser = User::where('contact_number',$userTypeCheck->contact_number)->first();
                    if($checkContactNumInUser !== null){
                        $checkIfUserOtpExist = Otp::where('email',$checkContactNumInUser->email)->where('contact_number',(string)$checkContactNumInUser->contact_number)->first();
                        if($checkIfUserOtpExist !== null){
                            Otp::where('id',$checkIfUserOtpExist->id)
                                ->where('contact_number',(string)$checkContactNumInUser->contact_number)
                                ->where('email',$checkContactNumInUser->email)
                                ->update([
                                    'otp_number'    => $otpNumber,
                                    'otp_expire'    => $checkIfUserOtpExist->updated_at->addSeconds(180)
                                ]);
                        }else{
                            $UserOtpCreated = Otp::create([
                                'email'         => $checkContactNumInUser->email,
                                'contact_number' => (string)$checkContactNumInUser->contact_number,
                                'otp_number'    => $otpNumber,
                            ]);
                            Otp::where('id',$UserOtpCreated->id)->update([
                                'otp_expire'    => $UserOtpCreated->created_at->addSeconds(180)
                            ]);
                        }
                        $text = 'Your OTP is: '.$otpNumber;
                        $emailcontent = array (
                            'text' => $text,
                            'title' => 'Thanks for Joining Wonga Live, Please use OTP for Completion of SignUp Process. You will need OTP to complete Sign Up Process.',
                            'userName' => $checkContactNumInUser->first_name
                        );
                        $details['email'] = $checkContactNumInUser->email;
                        $details['username'] = $checkContactNumInUser->first_name;
                        $details['subject'] = 'OTP Confirmation';
                        dispatch(new sendNotification($details,$emailcontent));
                        $data['otpNumber'] = $otpNumber;
                    }
                    // sent otp code
                    return response()->json(['status' => 'success','message' => 'Login successfully','data'=>$data], 200);
                }
            }
        }catch (Exception $e) {
            return response()->json(['status' => 'error','message' => 'Something went Wrong..!', 'data' => (object)[]],200);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Registration
    -------------------------------------------------------------------------------------------- */

        public function register(Request $request){
            $validation_array =[
                'email'                => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                'username'             => 'required',
                'contact_number'       => 'required|unique:users,contact_number,NULL,id,deleted_at,NULL',
                'name'           => 'required',
                'password'             => 'min:8|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation'=> 'required',
                'device_token'         => 'required',
            ];
            $validation = Validator::make($request->all(),$validation_array);
            if($validation->fails()){
                return response()->json(['status' => 'error','message'   => $validation->errors()->first(),'data'=> (object)[]]);
            }
            try {
                if($request->hasFile('avatar')){
                    $file = $request->file('avatar');
                    $extension = $file->getClientOriginalExtension();
                    $filename = Str::random(10).'.'.$extension;
                    Storage::disk('public')->putFileAs('avatar', $file,$filename);
                }else{
                    $filename = 'default.png';
                }
                $joindiamond = Setting::where('code','joindiamond')->first();

                $data['avatar']             =$filename;
                $data['email']              =request('email');
                $data['username']           =request('username');
                $data['contact_number']     =request('contact_number');
                $data['first_name']         =request('name');
                $data['password']           =bcrypt(request('password'));
                $data['user_type']          ='user';
                $data['status']             ='active';
                $data['sign_up_as']         ='app';
                $data['device_type']        =request('device_type');
                $data['device_token']       =request('device_token');
                $data['diamond']            =$joindiamond->value;
                $userdata = User::Create($data);
                $user = User::where('id',$userdata->id)->first();
                $data1['token'] = JWTAuth::fromUser($userdata);
                $data1['user'] = $user;
                $otpNumber = '';
                if(!empty($userdata)){
                    $otpNumber = random_int(1000, 9999);
                    //$otpNumber = (1234);
                    $UserOtpCreated = Otp::create([
                        'email'         => $user->email,
                        'contact_number' => (string)$user->contact_number,
                        'otp_number'    => $otpNumber,
                    ]);
                    $text = 'Your OTP is: '.$otpNumber;
                    $emailcontent = array (
                        'text' => $text,
                        'title' => 'Thanks for Joining Wonga Live, Please use Below OTP for Contact Verification.',
                        'userName' => $user->first_name
                    );
                    $details['email'] = $user->email;
                    $details['username'] = $user->first_name;
                    $details['subject'] = 'Welcome to Wonga Live, OTP Confirmation';
                    dispatch(new sendNotification($details,$emailcontent));
                }
                $data1['otpNumber'] = $otpNumber;
                $data['message'] =  'User Registration';
                $data['type'] = 'registered';
                $data['user_id'] = $userdata->id;
            return response()->json(['status' => 'success','message' => 'You are successfully Register..!','data' => $data1]);
        }catch (Exception $e) {
            return response()->json(['status' => 'error','message' => "Something went Wrong..."],200);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Send Otp
    -------------------------------------------------------------------------------------------- */

    public function sendOtp(Request $request){
        $validator =  Validator::make($request->all(),[
            'contact_number' => 'required|min:7'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'error','message'=> $validator->messages()->first()]);
        }
        try{
            $otpNumber = random_int(1000, 9999);
            //$otpNumber = (1234);
            $checkContactNumInUser = User::where('contact_number',$request->get('contact_number'))->first();
            if($checkContactNumInUser !== null){
                $checkIfUserOtpExist = Otp::where('email',$checkContactNumInUser->email)->where('contact_number',(string)$checkContactNumInUser->contact_number)->first();
                if($checkIfUserOtpExist !== null){
                    Otp::where('id',$checkIfUserOtpExist->id)
                        ->where('contact_number',(string)$checkContactNumInUser->contact_number)
                        ->where('email',$checkContactNumInUser->email)
                        ->update([
                            'otp_number'    => $otpNumber,
                            'otp_expire'    => $checkIfUserOtpExist->updated_at->addSeconds(180)
                        ]);
                }else{
                    $UserOtpCreated = Otp::create([
                        'email'         => $checkContactNumInUser->email,
                        'contact_number' => (string)$checkContactNumInUser->contact_number,
                        'otp_number'    => $otpNumber,
                    ]);
                    Otp::where('id',$UserOtpCreated->id)->update([
                        'otp_expire'    => $UserOtpCreated->created_at->addSeconds(180)
                    ]);
                }
                $text = 'Your OTP is: '.$otpNumber;
                $emailcontent = array (
                    'text' => $text,
                    'title' => 'Thanks for Joining Wonga Live, Please use Below OTP for Completion of SignUp Process. You will need OTP to complete Sign Up Process.',
                    'userName' => $checkContactNumInUser->first_name
                );
                $details['email'] = $checkContactNumInUser->email;
                $details['username'] = $checkContactNumInUser->first_name;
                $details['subject'] = 'OTP Confirmation';
                dispatch(new sendNotification($details,$emailcontent));
            }else{
                return response()->json(['status' => 'error','message'=> 'Mobile Number does not Exist.']);
            }
            return response()->json(['status'=> 'success','message' => 'Otp Sent Successfully..!','data'=> $otpNumber ]);
        }catch (\Exception $exception){
            return response()->json(['message'=> $exception->getMessage()]);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Verify Otp
    -------------------------------------------------------------------------------------------- */

    public function verifyOtp(Request $request){
        $validator =  Validator::make($request->all(),[
            'contact_number' => 'required|min:7',
            'otp_number'    => 'required|max:4|min:4'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'error','message'   => $validator->messages()->first()]);
        }
        try{
    
            $getOtpData = Otp::where('otp_number',$request->get('otp_number'))->where('contact_number',$request->get('contact_number'))->first();

            
            if($getOtpData !== null){
                if(Carbon::now() >= Carbon::parse()){
                    return response()->json(['status' => 'error','message' => 'Otp Expired']);
                }
                $getOtpuser1 = Otp::with('user')->where('contact_number',$request->get('contact_number'))->first();
                User::where('id', $getOtpuser1->user->id)->update(['otp_varifiy' => "1"]);
                // $getOtpuser = User::where('id', $getOtpuser1->user->id)->first();
                $getOtpuser = Otp::with('user')->where('contact_number',$request->get('contact_number'))->first();
                return response()->json(['status'    => 'success','message'   => 'OTP is Verified.','data' => $getOtpuser]);
            }
            return response()->json(['status'    => 'error','message'   => 'Invalid Otp',]);
        }catch (\Exception $exception){
            return response()->json(['message'   => $exception->getMessage()]);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Get Own User Profile
    -------------------------------------------------------------------------------------------- */

    public function getProfile(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if(!$user){
                return response()->json(['status'=>'error','message' => 'You are not able login from this application...'],200);
            }
            $user_data = User::where(['id'=>$user->id])->first();
            $data['user']  = $user_data;

            return response()->json(['status' => 'success','message' => 'User Profile Successfull','data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error','message' => "Something went Wrong..."],200);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Update Own Profile
    -------------------------------------------------------------------------------------------- */

    public function updateProfile(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if(!$user){
                return response()->json(['status'=>'error','message' => 'You are not able login from this application...'],200);
            }
            $user_data = User::where('id',$user->id)->first();
            if($user_data){
                if($request->hasFile('avatar')){
                    $file = $request->file('avatar');
                    $extension = $file->getClientOriginalExtension();
                    $filename = Str::random(10).'.'.$extension;
                    Storage::disk('public')->putFileAs('avatar', $file,$filename);
                }else if($user_data->avatar){
                    $filename = $user_data->avatar;
                }else{
                    $filename = '';
                }
                $checkcontactexist = User::where('contact_number', request('contact_number'))->first();
                if(!empty($checkcontactexist) && ($checkcontactexist->id !== $user->id)){
                    return response()->json(['status' => 'error','message' => 'Contact No already has been taken.']);
                }
                if(request('contact_number')){
                    $contact_number = request('contact_number');
                }else{
                    $contact_number = $user_data->contact_number;
                }
                $user_data->email           = request('email');
                $user_data->username      = request('username');
                $user_data->contact_number  = $contact_number;
                $user_data->first_name       = request('name');
                $user_data->avatar          = $filename;
                $user_data->save();
                
                $data ['user'] = $user_data;
                return response()->json(['status' => 'success','message' => 'Profile Update Successfully..!','data' => $data]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error','message' => $e->getMessage()], 200);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Change Password
    -------------------------------------------------------------------------------------------- */

    public function changePassword(Request $request){
        $validation_array =[
            'old_password'        => 'required|string|min:6',
            'new_password'        => 'required|string|min:6',
            'confirm_password'    => 'required|string|min:6',
        ];
        $validation = Validator::make($request->all(),$validation_array);
        if($validation->fails()){
            return response()->json(['status' => 'error','message' => $validation->messages()->first()],200);
        }
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if(!$user){
                return response()->json(['status' => 'error','message' => "Invalid Token..."],200);
            }
            if($user !== null){
                $password     = $user->password;
                $old_password = request('old_password');
                $new_password = request('new_password');
                $c_password   = request('confirm_password');
                if($new_password != $c_password){
                    return response()->json(['status' => 'error','message' => 'Your Password does not match with Above Password']);
                }
                if(isset($password)) {
                    if($old_password == $new_password){
                        return response(['status' => 'error','message'=>'New Password cannot be same as your current password. Please choose a different password.']);
                    }else{
                        if(\Hash::check($old_password, $password)){
                            $user->password = \Hash::Make($new_password);
                            $user->save();
                            return response()->json(['status' => 'success','message' => 'Your password change Successfully..!']);
                        }else{
                            return response()->json(['status' => 'error','message' => 'Your current password does not matches with the password you provided. Please try again.']);
                        }
                    }
                }else{
                    return response()->json(['status' => 'error','message' => 'User not available.']);
                }
            }else{
                return response()->json(['status'    => 'error','message'   => "You are not able login from this "]);
            }
        }catch(\Exception $e){
            return response()->json(['status'    => 'error','message'   => $e->getMessage()],200);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Logout
    -------------------------------------------------------------------------------------------- */

    public function logout(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if(!$user){
                return response()->json(['status'=>'error','message' => 'You are not able login from this application...'],200);
            }
            $user = User::find($user->id);
            User::where('id',$user->id)->update(['available_flag' => 'offline']);
            $user->save();
            JWTAuth::invalidate($request->token);
            return response()->json(['status'  => 'success','message' => 'User logged out Successfull..!']);
        }catch (\Exception $e) {
            return response()->json(['status'  => 'error','message' => $e->getMessage()]);
        }
    }

    
}