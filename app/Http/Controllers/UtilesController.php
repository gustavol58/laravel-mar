<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilesController extends Controller
{
    public function logout_own(Request $request){
        // instrucciones tomadas desde vendor\laravel\fortify\src\Http\Controllers\AuthenticatedSessionController.php@destroy()
        // $this->guard->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        // instrucciones tomadas desde la doc oficial de laravel 8
        Auth::logout();

        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/login');       
    }

}
