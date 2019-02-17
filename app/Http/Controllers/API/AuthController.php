<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use App\User; 
use Validator;

class AuthController extends Controller 
{
  /** 
   * Login API 
   * 
   * @return \Illuminate\Http\Response 
   */ 
  public function login(Request $request){ 
      $validator = Validator::make($request->all(), [ 
      
      'email' => 'required|email', 'password' => 'required', 
      'source' => 'required' ,'DevID' => 'required'
       ]);
    if(Auth::attempt(['email' => $request->email, 'password' => $request->password , 'source' => $request->source, 'DevID' => $request->DevID])){ 
      $user = Auth::user(); 
      $success['FName'] =  $user->firstName; $success['LName'] =  $user->lastName;$success['Email'] =  $user->email;
      $success['Gdr'] =  $user->gender; $success['BirtDate'] =  $user->birthDate;$success['CountryId'] =  $user->countryId;  $success['UserID'] =  $user->id; $success['Tkn'] =  $user->createToken('LaraPassport')->accessToken; 
    
      return response()->json([
        'result' => 'true',
        'userData' => $success
      ]); 
    } elseif ($validator->fails()){ 
        
      return response()->json(['error'=>$validator->errors()]); 
      
    } else { 
      return response()->json([
        'result' => 'error',
        'data' => 'Unauthorized Access'
      ]); 
    } 
  }
    
  /** 
   * Register API 
   * 
   * @return \Illuminate\Http\Response 
   */ 
  public function register(Request $request) 
  { 
    $validator = Validator::make($request->all(), [ 
      'firstName' => 'required',
      'lastName' => 'required',
      'email' => 'required|email', 
      'password' => 'required', 
      'c_password' => 'required|same:password',
      'gender' => 'required','birthDate' => 'required','countryId' => 'required','source' => 'required' ,'DevID' => 'required'
      
    ]);
    if ($validator->fails()) { 
      return response()->json(['error'=>$validator->errors()]);
    } 
    
    elseif (Auth::attempt(['email' => $request->email , 'password' => $request->password])){ 
        
       return response()->json([
           'code' => '1000',
           'message'=>'Email registered before'
           ]); 
      
     }
    $postArray = $request->all(); 
    $postArray['password'] = bcrypt($postArray['password']); 
    $user = User::create($postArray); 
 
    $success['FName'] =  $user->firstName; $success['LName'] =  $user->lastName;$success['Email'] =  $user->email;
    $success['Gdr'] =  $user->gender; $success['BirtDate'] =  $user->birthDate;$success['CountryId'] =  $user->countryId;  $success['UserID'] =  $user->id; $success['Tkn'] =  $user->createToken('LaraPassport')->accessToken; 
    
    return response()->json([
      'result' => 'true',
      'userData' => $success,
    ]); 
  }
    
}
