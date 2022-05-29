<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    // 19may2022 
    public $arr_para_roles = [];

    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 20may2022:
        // Leer el array que tiene los nombres cortos y largos de los roles:
        $arr_roles_aux = [];
        $arr_roles_largos = config('constantes.roles_nombres_largos'); 
        
        $sql1 = "SELECT id,name FROM roles WHERE 1";
        $coll_para_roles = collect(DB::select($sql1));
        foreach ($coll_para_roles as $un_rol) {
            $arr_roles_aux['id'] = $un_rol->id;
            $arr_roles_aux['rol'] = $arr_roles_largos[$un_rol->name];
            array_push($this->arr_para_roles , $arr_roles_aux);
        }

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // 20may2022: Para usar el nuevo campo 'state'
        Fortify::authenticateUsing(function (Request $request)  {
            // 20may2022: se valida el nuevo campo 'state' de la tabla users:
            $user = User::where('state', 1)
                ->where(function($query) use ($request) {
                    $query->where('email', $request->email)
                          ->orWhere('user_name', $request->email);
                }) 
                ->first();
    
            if ($user &&
                \Hash::check($request->password, $user->password)) {
                return $user;
            }
        });  
        
        // 19may2022: Para poder enviar los roles a register.blade.php:
        Fortify::registerView(function(){
            return view('auth.register')->with('arr_para_roles',$this->arr_para_roles);;
        });        

    }
}
