<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function login(Request $request){
        $email = $request->usuario;
        $password = $request->clave;
        if (Auth::attempt(['usuario' => $email, 'password' => $password])) {
            dd(Auth::user());
            return 'Tu session ha iniciado correctamente';
        }
        return 'crredenciales incorrecta';
        
   }
}
