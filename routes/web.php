<?php

// use Illuminate\Support\Facades\Route;
use App\Http\Livewire\FirstOption;
use App\Http\Controllers\UtilesController;
use App\Http\Controllers\GenerarHojaElectronicaController;
use App\Http\Livewire\CrearRecaudos;
use App\Http\Livewire\AsentarRecaudos;
use App\Http\Livewire\InfoRecaudos;
use App\Http\Livewire\VerRecaudos;
use App\Http\Livewire\ModificarRecaudo;
use App\Http\Livewire\AprobarRecaudos;
use App\Http\Livewire\AnularRecaudos;
use App\Http\Livewire\ExtractosBancarios;
use App\Http\Livewire\ImportarConsignaciones;
use App\Http\Livewire\Pedidos\ConfigFormu\Admin;
use App\Http\Livewire\Pedidos\ConfigFormu\ConfigIndex;
use App\Http\Livewire\Pedidos\ConfigFormu\ConfiGral;
use App\Http\Livewire\Pedidos\ConfigFormu\InputSeccion;
use App\Http\Livewire\Pedidos\ConfigFormu\InputTexto;
use App\Http\Livewire\Pedidos\ConfigFormu\InputNumero;
use App\Http\Livewire\Pedidos\ConfigFormu\InputSeleccion;
use App\Http\Livewire\Pedidos\ConfigFormu\InputCasilla;
use App\Http\Livewire\Pedidos\ConfigFormu\InputRadio;
use App\Http\Livewire\Pedidos\ConfigFormu\InputEmail;
use App\Http\Livewire\Pedidos\ConfigFormu\InputFecha;
use App\Http\Livewire\Pedidos\ConfigFormu\InputArchivo;
use App\Http\Livewire\Pedidos\ConfigFormu\InputMultivariable;
use App\Http\Livewire\Pedidos\ConfigFormu\InputOrdenar;
use App\Http\Livewire\Pedidos\ConfigFormu\InputEliminar;
use App\Http\Livewire\Pedidos\ConfigFormu\ModificarNombreLargo;
use App\Http\Livewire\Pedidos\ConfigFormu\ModificarPrefijo;
use App\Http\Livewire\Pedidos\ConfigFormu\ModificarCabeceraCampo;
use App\Http\Livewire\Pedidos\Formu\EscogerTipo;
use App\Http\Livewire\Pedidos\Formu\VerFormu;
use App\Http\Livewire\Pedidos\Formu\CrearFormu;
use App\Http\Livewire\Pedidos\Formu\ModificarFormu;
use App\Http\Livewire\Clientes\VerCliente;
use App\Http\Livewire\Clientes\CrearCliente;
use App\Http\Livewire\Pedidos\Nucleo\VerPedido;
use App\Http\Livewire\Pedidos\Nucleo\CrearPedido;
use App\Http\Livewire\Usuarios\VerUsuario;
// 16may2022: 
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Livewire\Usuarios\ModificarUsuario;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// 18sep2021: No se usará mas el componente FirstOption ya que a partir de ahora 
// se unen los módulos de recaudos y pedidos (no se usará más el módulo de 
// pedidos anterior que era llamado por el componente FirstOption)
// Route::get('/', FirstOption::class);
Route::redirect('/', 'login');

Route::post('logout-own', [UtilesController::class , 'logout_own'])->name('logout-own');

// Rutas a las que puede acceder cualquier usuario que esté logueado: 
Route::group(['middleware' => 'auth'], function () {
    Route::get('/crear-recaudos', CrearRecaudos::class)->name('crear-recaudos');
    Route::get('/modificar-recaudo/{id}', ModificarRecaudo::class)->name('modificar-recaudo');
    Route::get('/ver-recaudos', VerRecaudos::class)->name('ver-recaudos');
    Route::get('/info-recaudos', InfoRecaudos::class)->name('info-recaudos');
    Route::post('/hoja-info-recaudo', [GenerarHojaElectronicaController::class, 'exportar_info_recaudo'])->name('hoja-info-recaudo');
});

// Rutas a las que pueden acceder admin y contab: 
Route::group(['middleware' => ['role:admin|contab']], function () {
    Route::get('/asentar-recaudos', AsentarRecaudos::class)->name('asentar-recaudos');
});

// Rutas a las que pueden acceder admin y comer: 
Route::group(['middleware' => ['role:admin|comer']], function () {
    Route::get('/ver-clientes', VerCliente::class)->name('ver-clientes');
    Route::get('/crear-cliente/{operacion}/{modificar_cliente_id?}', CrearCliente::class)->name('crear-cliente');
    Route::get('/crear-pedido/{operacion}/{modificar_pedido_encab_id?}', CrearPedido::class)->name('crear-pedido');
});

// Rutas a las que pueden acceder admin, comer, produ, disen: 
Route::group(['middleware' => ['role:admin|comer|produ|contab|disen']], function () {
    // Rutas para el módulo de pedidos:
    Route::get('/escoger-tipo_producto', EscogerTipo::class)->name('escoger-tipo_producto');
    Route::get('/ver-formu/{tipo_producto_id}', VerFormu::class)->name('ver-formu');
    // 24dic2021; nuevos parámetros operación y formu__id:
        Route::get('/crear-formu/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}/{operacion}/{tipo_producto_recibido_prefijo?}/{formu__id?}/{formu__codigo_producto?}/{formu__estado_nombre?}', CrearFormu::class)->name('crear-formu');
        Route::get('/ver-pedidos', VerPedido::class)->name('ver-pedidos');
    });

// Rutas a las que solo puede acceder admin: 
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/aprobar-recaudos', AprobarRecaudos::class)->name('aprobar-recaudos');  
    Route::get('/extractos-bancarios', ExtractosBancarios::class)->name('extractos-bancarios');
    Route::get('/importar-consignaciones/{arr_consignaciones_param}/{arr_no_cargadas_param}/{extracto_nombre_param}/{extracto_provisional_param}', ImportarConsignaciones::class)->name('importar-consignaciones');
    Route::get('/ver-usuarios', VerUsuario::class)->name('ver-usuarios');
    // 18may2022:
    // Para evitar que /register cambie el usuario logueado cuando crea el nuevo usuario:
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);


    // Rutas para el módulo de pedidos:
    Route::get('/generar-formu-admin', Admin::class)->name('generar-formu-admin');
    Route::get('/generar-formu-index/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', ConfigIndex::class)->name('generar-formu-index');
    Route::get('/generar-config-gral/{encab_titulo}/{encab_subtitulo}/{encab_columnas}/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', ConfiGral::class)->name('generar-config-gral');
    Route::get('/generar-seccion/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputSeccion::class)->name('generar-seccion');
    Route::get('/generar-texto/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputTexto::class)->name('generar-texto');
    Route::get('/generar-numero/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputNumero::class)->name('generar-numero');
    Route::get('/generar-seleccion/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputSeleccion::class)->name('generar-seleccion');
    Route::get('/generar-casilla/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputCasilla::class)->name('generar-casilla');
    Route::get('/generar-radio/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputRadio::class)->name('generar-radio');
    Route::get('/generar-email/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputEmail::class)->name('generar-email');
    Route::get('/generar-fecha/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputFecha::class)->name('generar-fecha');
    Route::get('/generar-archivo/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputArchivo::class)->name('generar-archivo');
    Route::get('/generar-multivariable/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputMultivariable::class)->name('generar-multivariable');
    Route::get('/generar-ordenar/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputOrdenar::class)->name('generar-ordenar');
    Route::get('/generar-eliminar/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', InputEliminar::class)->name('generar-eliminar');
    Route::get('/modificar-nombre-largo/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', ModificarNombreLargo::class)->name('modificar-nombre-largo');
    Route::get('/modificar-prefijo/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}/{tipo_producto_recibido_prefijo?}', ModificarPrefijo::class)->name('modificar-prefijo');
    Route::get('/modificar-cabecera-campo/{formu_detalle_id}/{cabecera_actual}/{tipo_producto_recibido_id}/{tipo_producto_recibido_nombre}/{tipo_producto_recibido_slug}', ModificarCabeceraCampo::class)->name('modificar-cabecera-campo');
    Route::get('/modificar-usuario/{modificar_usuario_id}', ModificarUsuario::class)->name('modificar-usuario');
});


// llamar una ruta de un controlador NOLIVEWIRE, en laravel 8:
// Route::post('/hoja-info-recaudo', [GenerarHojaElectronicaController::class, 'exportar_info_recaudo'])->name('hoja-info-recaudo');


// Rutas para limpiar cache y archivo config desde un hosting compartido: 
Route::get('cache-clear' , function(){
    $exitCode = Artisan::call('cache:clear');
    echo "limpiada...";
});

Route::get('cache-config' , function(){
    $exitCode = Artisan::call('config:cache');
    echo "config actualizado...";
});
