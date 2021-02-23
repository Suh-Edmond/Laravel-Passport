<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class ApiController extends Controller
{
    //register user function
    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|email|unique:users',
            'telephone' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'email_verified_at' => now()
        ]);
        //hashpassword
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response()->json(['user' => $user, 'access_token' => $accessToken, "status" => 201]);
    }

    //login user function
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => "required|email",
            'password' => 'required|min:8'
        ]);

        if (!auth()->attempt($loginData)) {
            return response()->json(['userData' => $loginData, 'message' => "Invalid Credentials", "status" => "401"]);
        } else {
            $user = auth()->user();
            $accessToken = $user->createToken('authToken')->accessToken;
            $cookie = $this->getCookieDetails($accessToken);

            return response()->json(["msg" => "Successfully Login",  "access_token" => $accessToken, "status" => 200, "data" => $user])
                ->cookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['minutes'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httponly'],
                    $cookie['samesite']
                );
        }
    }
    //get user details function
    public function UserDetails()
    {
        $user = Auth::user();
        return response()->json([$user], 200);
    }
    //logout user function
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $cookie = Cookie::forget('_token');
        return response()->json(["msg" => "Successfully Logout", "status" => "200"])
            ->withCookie($cookie);
    }
    //update user function
    public function updateUser(Request $request, $id)
    {

        $updatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|email',
            'telephone' => 'required',
            'password' => 'required|string|min:8',
        ]);
        $updatedData['password'] = Hash::make($updatedData['password']);
        $updated = User::findOrFail($id)->update($updatedData);
        return response()->json(["data" => $updated, "msg" => "Successfully updated details", "status" => 200]);
    }
    //get cookie details function
    private function getCookieDetails($token)
    {
        return [
            'name' => '_token',
            'value' => $token,
            'minutes' => 1440,
            'path' => null,
            'domain' => null,
            // 'secure' => true, // for production
            'secure' => null, // for localhost
            'httponly' => true,
            'samesite' => false,
        ];
    }

    //register admin users
    public function adminRegisteration(Request $request)
    {
        $validatedData = $request->validate([
            'name' => "required",
            'email' => 'required|email',
            'telephone' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        //has the password
        $validatedData['password'] = Hash::make($validatedData['password']);
        $newAdmin = User::create($validatedData);
        $accessToken = $newAdmin->createToken('AdminToken', ['delete', 'add_product'])->accessToken;

        return response()->json(['msg' => "Admin Successfully Created", "status" => 201, "token" => $accessToken]);
    }

    //login admin users
    public function adminLogin(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);
        if (!auth()->attempt($loginData)) {
            return response()->json(["msg" => "Invalid Credential", "status" => 401, "data" => $loginData]);
        } else {
            $newAdmin = auth()->user();
            $accessToken = $newAdmin->createToken("AdminToken", ['delete', 'add_product'])->accessToken;
            $cookie = $this->getCookieDetails($accessToken);
            return response()->json(["msg" => "Admin Successfully Login",  "access_token" => $accessToken, "status" => 200, "data" => $newAdmin])
                ->cookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['minutes'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httponly'],
                    $cookie['samesite']
                );
        }
    }
    //logout admin user
    public function adminLogout(Request $request)
    {
        $request->user()->token()->revoke();
        $cookie = Cookie::forget('_token');
        return response()->json(["msg" => "Successfully Logout", "status" => "200"])
            ->withCookie($cookie);
    }
}
