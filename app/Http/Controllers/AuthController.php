<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Notifications\AccountActivate;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UnprocessEntityException;

class AuthController extends Controller
{

  public function login(Request $request) {
    $requestBody = $request->json()->all();
    $validation = Validator::make($requestBody, [
      'email' => 'required|string|email',
      'password' => 'required|string'
    ]);

    if($validation->fails()) throw new ValidationException($validation);

    return $this->doLogin($requestBody['email'], $requestBody['password'], 'partner');
  }

  public function loginAdmin(Request $request) {
    $requestBody = $request->json()->all();
    $validation = Validator::make($requestBody, [
      'email' => 'required|string|email',
      'password' => 'required|string'
    ]);

    if($validation->fails()) throw new ValidationException($validation);

    return $this->doLogin($requestBody['email'], $requestBody['password'], 'superadmin');
  }

  private function handleResponse ($response) {
    return response()->json([
      'user_id' => $response['user']->id,
      'name' => $response['user']->name,
      'email' => $response['user']->email,
      'role' => $response['user']->role->name ?? null,
      'token_type' => 'Bearer',
      'expires_at' => Carbon::parse($response['token']->token->expires_at)->toDateTimeString(),
      'email_verified_at' => $response['user']->email_verified_at,
      'login_at' => $response['user']->login_at,
      'access_token' => $response['token']->accessToken,
    ], 200);
  }

  private function doLogin($email, $password, $role) {
    $credentials = [
      'email' => $email, 
      'password' => $password,
      'is_active' => true
    ];

    if(!Auth::attempt($credentials)) {
      throw new AuthenticationException('Email or password you entered is incorrect!');
    }


    try {
      $user = Auth::user();
      $user = User::with('role')->find($user->id);
      
      if(!$user){
        throw new UnprocessEntityException('Login failed! Proccess has been failed');
      }
      
      $tokenResult = $user->createToken('Laravel Password Grant Client');
      $token = $tokenResult->token;
      
      $token->save();
  
      $response = [
        'user' => $user, 
        'token' => $tokenResult 
      ];

      return $this->handleResponse($response);
    } catch (UnprocessEntityException $e) {
      return response()->json($e);
    }
  }

  public function logout(Request $request) {
    $request->user()->token()->revoke();

    return response()->json([
      'message' => 'Successfully logged out'
    ]);
  }

  public function activate($token) {
    $user = User::where('activation_token', $token)->first();

    if(!$user) throw new ModelNotFoundException('This Activation token is invalid');

    $user->email_verified_at = Carbon::now();
    $user->save();

    return redirect()->away(config('additional.frontend_url'));
  }
  
  public function user(Request $request) {
    $userId = $request->user()->id;
    $key = base64_encode('partner-'.$userId);
    
    $user = Cache::remember($key, 14400, function () use ($userId) {
      return User::with(['partner'])->find($userId);
    });
    
    return response()->json($user);
  }

  public function checkVerified(Request $request) {
    if($request->user() && $request->user()->hasVerifiedEmail()){
      return response()->json([
        'message' => 'Email already verified'
      ]);
    }

    return response()->json([
      'message' => 'Email has been not verified'
    ]);
  }

  public function sendVerify(Request $request) {
    $requestBody = $request->json()->all();

    if($request->user() && $request->user()->hasVerifiedEmail()){
      return response()->json([
        'message' => 'Email already verified'
      ]);
    }
    $user = $request->user();

    if(isset($requestBody['term_and_condition'])) {
      $user->term_and_condition = $requestBody['term_and_condition'];
      $user->save();
    }
    $token = PasswordReset::updateOrCreate([
      'email' => $user->email,
      'type' => 'email_verification'
    ],[
      'type' => 'email_verification',
      'email' => $user->email,
      'token' => Str::random(60)
    ]);

    $user->notify(new AccountActivate($token->token));

    return response()->json([
      'message' => 'Verification Email has been sent to your mail'
    ]);
  }

}
