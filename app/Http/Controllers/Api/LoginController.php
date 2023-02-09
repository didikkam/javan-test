<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
   public function login(Request $request)
   {
      $request->validate([
         'email' => 'required|exists:users,email',
         'password' => 'required',
      ]);
      if (!Auth::attempt($request->only('email', 'password'))) {
         return response([
            'status'    => true,
            'message'   => 'Email / Password Salah',
            'data'      => null
         ], Response::HTTP_BAD_REQUEST);
      }
      $user = Auth::user();
      $token = $user->createToken('token')->plainTextToken;
      $cookie = cookie('jwt', $token, 60 * 24); // 1 day

      return response([
         'status' => true,
         'message' => 'you have successfully logged in',
         'data' => $user
      ])->withCookie($cookie);
   }
}
