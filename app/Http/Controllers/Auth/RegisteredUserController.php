<?php

// 18may2022:
// La razón de ser de este controlador que SUSTITUYE al controlador por defecto
// suministrado por Fortify (........vendor/laravel/fortify/src/Http/Controllers/RegisteredUserController.php) 
// es evitar que /register cambie el usuario logueado cuando crea el nuevo usuario:

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;

class RegisteredUserController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the registration view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\RegisterViewResponse
     */
    public function create(Request $request): RegisterViewResponse
    {
        return app(RegisterViewResponse::class);
    }

    /**
     * Create a new registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Laravel\Fortify\Contracts\RegisterResponse
     */
    public function store(Request $request,
                          CreatesNewUsers $creator): RegisterResponse
    {
        event(new Registered($user = $creator->create($request->all())));

        // 18may2022: Esta es la instrucción que cambia el usuario logueado:
        // $this->guard->login($user); 

// dd($request->php_rol);  
// 19may2022       
// la siguiente instrucción le asignará el rol al usuario recién creado:
        $user->assignRole($request->php_rol); 


        return app(RegisterResponse::class);
    }
}