<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Mail;
use Event;
use Illuminate\Support\Arr;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Stripe\Charge;
use Stripe\Customer;
use App\Models\User;
use App\Models\Setting;
use App\Models\Cms;
use App\Models\Otp;
use Response;
use App\Helpers\Helper;

class CommonController extends Controller{
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
    @Description: Function for CMS Page
    -------------------------------------------------------------------------------------------- */

    public function CmsPage(Request $request){
        try{
            $allcms = json_decode(strip_tags(Cms::where('status','active')->get()),true);
            return response()->json(['status' => 'success','message' =>'All CMS Pages Get Successfully Done..!','data' => $allcms]);
        }catch(Exception $e){
            return response()->json(['status' => 'error','message' => $e->getMessage()]);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Update Token
    -------------------------------------------------------------------------------------------- */

    public function updateToken(Request $request){
        $token = $request->header('Authorization');
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $token = JWTAuth::refresh(str_replace('Bearer ',"",$token));
        } catch (Exception $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => "error",'code'=>500,'message' => 'Token is Invalid']);
            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                $token = JWTAuth::refresh(str_replace('Bearer ',"",$token));
                return response()->json(['status' => "error",'code'=>$e->getCode(),'message' => 'Token is Expired','token'=>$token]);
            }
        } catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json(['status' => "error",'code'=>500,'message' => 'Token is Invalid']);
        } catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            $token = JWTAuth::refresh(str_replace('Bearer ',"",$token));
            return response()->json(['status' => "error",'code'=>$e->getCode(),'message' => 'Token is Expired','token'=>$token]);
        } catch(JWTAuthException $e){
            return response()->json(['status' => "error",'code'=>$e->getCode(),'message' => $e->getMessage()]);
        }
        return response()->json(['status' => "success",'message' =>"Token Success",'token'=>str_replace('Bearer ',"",$token)]);
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Forgot Password
    -------------------------------------------------------------------------------------------- */

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'type' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['status'   => 'error','message'  => $validator->messages()->first()]);
        }
        try{
            $user = User::where(['email'=>$request->email,'user_type'=>$request->type])->first();
            if(!$user){
                return response()->json(['status'    => 'error','message'   => "Please Correct E-mail Address..!"]);
            } else {
                $password = Str::random(8);
                $mailData['mail_to']   = $user->email;
                $mailData['to_name']   = $user->first_name;
                $mailData['mail_from']   = 'admin@admin.com';
                $mailData['from_title']  = 'Reset Password';
                $mailData['subject']     = 'Reset Password';
                $data = [
                    'data' => $mailData,
                    'username'=>$user->first_name,
                    'password'=>$password
                ];
                Mail::send('emails.verify', $data, function($message) use($mailData) {
                    $message->subject($mailData['subject']);
                    $message->from($mailData['mail_from'],$mailData['from_title']);
                    $message->to($mailData['mail_to'],$mailData['to_name']);
                });
                if(Mail::failures()) {
                    return response()->json(['status'=>'error','message'=>'Mail failed']);
                }
                $user->password = \Hash::make($password);
                $user->link_code = \Hash::make($password);
                $user->save();
                return response()->json(['status'    => 'success','message'   => 'New Password is been Sent to your e-mail..!',]);
            }
        }catch(Exception $e){
            return response()->json(['status'    => 'error','message'   => $e->getMessage()]);
        }
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function for Reset Passeord
    -------------------------------------------------------------------------------------------- */

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email'  => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        if($validator->fails()) {
            return response()->json(['status'   => 'error','message'  => $validator->messages()->first()]);
        }
        try{
            $user = User::where('email', $request->email)->where(function ($query) {
                $query->where('user_type','user');
                $query->where('status','active');
            })->first();
            if(!$user){
                return response()->json(['status'    => 'error','message'   => "Please Correct E-mail Address..!"]);
            }else{
                $today = Carbon::now();
                $linkEx_time = Carbon::parse($user->link_expire);
                if($today >= $linkEx_time){
                    return response(['status'    => 'error','message'   =>  'Your link is expired. please try again & generate new link.']);
                }else{
                    if(request('password') == $user->link_code){
                        $user->password = bcrypt(request('password'));
                        $user->save();
                        return response()->json(['status'    => 'success','message'   => 'Your Password Changed Successfully..!',]);
                    }else{
                        return response()->json(['status'    => 'error','message'   => 'Your Password Does not Match',]);
                    }
                }
            }
        }catch(Exception $e){
            return response()->json(['status'    => 'error','message'   => $e->getMessage()]);
        }
    }
}
