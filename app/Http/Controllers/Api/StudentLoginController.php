<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash, Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $user = User::where('email', $request->email)->first();
        // if (! $user || ! Hash::check($request->password, $user->password)) {
            // throw \ValidationException::withMessages([
            //     'email' => ['The provided credentials are incorrect.'],
            // ]);
        if(Auth::attempt($credentials)){
            return response([
                'status' => true,
                // 'token' => $user->createToken('token')->plainTextToken
                'token' => $user->createToken('token')->accessToken
            ]);
        }
        return response([
            'status' => false,
            'message' => 'The provided credentials are incorrect'
        ]);
       
    }

    public function getuserDetails(Request $request){

            $user = $request->user();
            
            if(!empty($user)){
                return response([
                    'status' => true,
                    'user' => $user
                ]);
            }
            return response([
                'status' => false,
                'mesaage' => 'User not found'
            ]);
    }

    public function createUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);
      
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create($request->all());

        if(isset($user)){
            return response([
                'status' => true,
                'message' => 'User create successfully!'
            ]);
        }
        return response(['status' => false, 'message' => 'Something went wrong!']);
    }

    public function updateUser(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required',
        ]);
      
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::where('id',$id)->update($input);

        if(isset($user)){
            return response([
                'status' => true,
                'message' => 'User update successfully!'
            ]);
        }

        return response(['status' => false, 'message' => 'Something went wrong!']);
        
    }

    public function deleteUser($id){

        $user = User::find($id)->delete();
        if(isset($user)){
            return response([
                'status' => true,
                'message' => 'User deleted successfully!'
            ]);
        }

        return response(['status' => false, 'message' => 'Something went wrong!']);

    }

    public function logout(Request $request){
        $user = $request->user();
        $user->tokens()->delete();
        return response(['status' => true, 'message' => 'logout successfully!']);
    }
}
