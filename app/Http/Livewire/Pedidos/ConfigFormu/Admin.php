<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Pedidos\FormuTipoProducto;
use App\Models\Pedidos\FormuTabla;
use App\Models\Pedidos\FormuCampo;
use App\Models\Parametro;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Admin extends Component
{
    public $modal_visible_admin;

    public $admin_cabecera;   
    public $admin_prefijo;   

    // longitud prefijo es de 234 caracteres porque  el CODIGO de los productos, que es un
    // varchar(254), se forma con prefijo+fecha+id (fecha+id puede sumar 20 caracteres)
    protected $rules = [            
        'admin_cabecera' => 'required|max:254|unique:formu_tipo_productos,tipo_producto_nombre',
        'admin_prefijo' => 'nullable|min:2|max:234|unique:formu_tipo_productos,prefijo',
    ];  

    protected $messages = [
        'admin_cabecera.required' => 'Debe digitar el nombre del tipo de producto.',
        'admin_cabecera.max' => 'El nombre del tipo de producto no puede tener más de 254 caracteres.',
        'admin_cabecera.unique' => 'El tipo de producto digitado ya existe.',
        'admin_prefijo.min' => 'El prefijo no puede tener menos de 2 letras mayúsculas.',
        'admin_prefijo.max' => 'El prefijo no puede tener más de 234 letras mayúsculas.',
        'admin_prefijo.unique' => 'El prefijo digitado ya existe.',
    ];   

    public function mount(){
        // $this->modal_visible_admin = true;
    }    

    public function render(){ 
        // el 'id as editar_prefijo' es solo para llevar alli true o false:
        $tipos_producto = FormuTipoProducto::select('id' , 'tipo_producto_nombre' , 'prefijo' , 'tipo_producto_slug' , 'id as editar_prefijo')->where('id' , '<>' , 1)->orderBy('tipo_producto_nombre')->get();
        // Recorre el resultado para determinar cuáles tablas formu__.... ya tienen registros:         
        foreach($tipos_producto as $key => $fila){
            $sql = "select count(1) nro_rgtos 
                from formu__" . $fila['tipo_producto_slug'];   
            $obj_resultado = collect(DB::select($sql));
            if($obj_resultado[0]->nro_rgtos == 0){
                $tipos_producto[$key]['editar_prefijo'] = true;
            }else{
                $tipos_producto[$key]['editar_prefijo'] = false;
            } 
        }
        return view('livewire.pedidos.config-formu.admin' , compact('tipos_producto'));
    }

    public function mostrar_modal_admin(){
        $this->reset(['admin_cabecera' ]);
        $this->reset(['admin_prefijo' ]);
        $this->resetValidation();        
        $this->modal_visible_admin = true;
        $this->emit('focusInicial');
    }      

    public function cerrar_modal_admin(){
        $this->modal_visible_admin = false;
        // return redirect(url('generar-formu-etiquetas'));
    }

    public function submit_admin(){
        $this->validate();
        $this->modal_visible_admin = false;

        // 08ago2021: 
        // A partir de hoy el slug (nombre corto del tipo de producto) estará 
        // formado asi: 
        //      slug aplicado al nombre largo
        //      guion al piso 
        //      consecutivo traido desde la tabla parametros 
        $registro_parametros = Parametro::find(1);
        $consecutivo = $registro_parametros['sufijo_tipo_producto'];
        $consecutivo++;
        $registro_parametros->sufijo_tipo_producto = $consecutivo;
        $registro_parametros->save();

        // armar el slug (o nombre corto) del tipo de producto
        $slug = str_replace('-','_',Str::slug(substr($this->admin_cabecera , 0 , 8))) . "_" . $consecutivo; 

// dd($slug);       
        $columnas = 2;    

        // 07ago2021: 
        // Debido a que el Schema::create no funciona bien dentro de beginTransaction, 
        // es necesario hacerlo antes de todas las demás modificaciones b.d.: 
        // error_reporting(E_ALL);  // no hay necesidad, lo importante es usar \Throwable
        $correcto_schema = true;
        try {
            // Crea la tabla asociada al nuevo tipo de producto: 
            $nueva_tabla = "formu__" . $slug;
            Schema::create($nueva_tabla , function (Blueprint $table) {
                // 07oct2021: 
                // Error que generó constantes mensajes FOREIGN KEY NOT VALID: 
                // el increment debe ser BIGINT UNSIGNED:
                // $table->increments('id');
                $table->bigIncrements('id');
                $table->string('codigo' , 254)->nullable()->comment('Null o: AAA...AAA-AAAAMMDD-###....: Letras mayúsculas del prefijo , guión medio, fecha AAAAMMDD, guión medio, id de la tabla formu__....');
                $table->timestamps();
                $table->unsignedBigInteger('user_id');            
    
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');                 
            });
        } catch (\Throwable $th) {
            $correcto_schema = false;
            // envío de email al programador:
            $destinatario = 'catchen@tavohen.com';
            $asunto = "Markka-Catch: No se pudo crear formu__.....";
            $titulo_para_email = "Catch " . date("Y-m-d h:i:s a");
            $titulo_para_usuario = "El tipo de producto " . $this->admin_cabecera . " no pudo ser agregado.<br>";
            $cuerpo_para_usuario = "No fue posible agregar la tabla del tipo de producto. Por favor envie la imagen de esta pantalla al programador del sistema.<br>" . date("Y-m-d h:i:s a");
            $cuerpo_para_email = "<br><br>Catch al tratar de crear la tabla: " . $nueva_tabla . " en /markka-pruebas22/laravel/app/Http/
            Livewire/Pedidos/ConfigFormu/Admin.php:" . "<br><br><br><pre>" . $th . "</pre>";

            $contenido = [
                'titulo_para_email' => $titulo_para_email,
                'cuerpo_para_email' => $cuerpo_para_email,
                'titulo_para_usuario' => $titulo_para_usuario,
                'cuerpo_para_usuario' => $cuerpo_para_usuario
            ];
            \Mail::to($destinatario)->send(new \App\Mail\enviosCatch($asunto , $contenido));            
        }

        if($correcto_schema){
            // transacciones a la base de datos: 
            DB::beginTransaction();
            try {
                // Crea el tipo de producto en formu_tipo_productos: 
                $nuevo_tipo_producto = FormuTipoProducto::create([
                    'prefijo' => $this->admin_prefijo,
                    'tipo_producto_nombre' => $this->admin_cabecera,
                    'tipo_producto_slug' => $slug,
                    'columnas' => $columnas,
                    'user_id' => Auth::user()->id,
                ]);
        
                // agrega en formu_tablas la tabla que acaba de ser creada. 
                // El alias se actualizará después de obtener el id tabla: 
                $nuevo_rgto_tabla = FormuTabla::create([
                    'tipo_producto_id' => $nuevo_tipo_producto->id,
                    'nombre' => $nueva_tabla,
                    'alias' => '',
                    'titulo' => $this->admin_cabecera,
                ]); 
                $nuevo_rgto_tabla->alias = "f" . $nuevo_rgto_tabla->id;
                $nuevo_rgto_tabla->save();
            
                // 12ago2021:
                // agregar en formu_campos los tres primeros registros: id, codigo y user_id 
                // (es decir: los campos para guardar en el futuro el id autoincrementable 
                // de cada producto creado en formu__...., y: el código que será asignado 
                // al nuevo producto (el código es ####...####-fecha-id),  y el usuario
                // que haya creado el producto en formu__....)
                $nuevo_rgto_campo_id_alias = $nuevo_rgto_tabla->alias . ".id";
                FormuCampo::create([
                    'tabla_id' => $nuevo_rgto_tabla->id,
                    'nombre' => 'id',
                    'alias' => $nuevo_rgto_campo_id_alias,
                    'titulo' => 'Número',
                    'titulo_visible' => 0,
                    'left_joins' => '',
                    'equivalentes' => '',
                ]); 

                $nuevo_rgto_campo_id_alias = $nuevo_rgto_tabla->alias . ".codigo";
                FormuCampo::create([
                    'tabla_id' => $nuevo_rgto_tabla->id,
                    'nombre' => 'codigo',
                    'alias' => $nuevo_rgto_campo_id_alias,
                    'titulo' => 'Código',
                    'titulo_visible' => 1,
                    'left_joins' => '',
                    'equivalentes' => '',
                ]); 
        
                $nuevo_rgto_campo_user_id_alias = "usu" . $nuevo_rgto_tabla->alias . ".name";
                FormuCampo::create([
                    'tabla_id' => $nuevo_rgto_tabla->id,
                    'nombre' => 'user_id',
                    'alias' => $nuevo_rgto_campo_user_id_alias,
                    'titulo' => 'Creado por',
                    'titulo_visible' => 1,
                    'left_joins' => ' left join users usu' . $nuevo_rgto_tabla->alias . ' on usu' . $nuevo_rgto_tabla->alias . ".id = " . $nuevo_rgto_tabla->alias . ".user_id" ,
                    'equivalentes' => '',
                ]); 
                DB::commit();
            }catch (\Throwable $th) {
                // devolver las transacciones:
                DB::rollback();
                // debe devolver el schema::table: 
                Schema::dropIfExists($nueva_tabla);                  
                // envío de email al programador:
                $destinatario = 'catchen@tavohen.com';
                $asunto = "Markka-Catch: transacción.";
                $titulo_para_email = "Catch " . date("Y-m-d h:i:s a");
                $titulo_para_usuario = "El tipo de producto " . $this->admin_cabecera . " no pudo ser agregado.<br>";
                $cuerpo_para_usuario = "Pueden haber varias causas distintas. Por favor envie la imagen de esta pantalla al programador del sistema.<br>" . date("Y-m-d h:i:s a");
                $cuerpo_para_email = "<br><br>Catch en una de las transacciones de: /markka-pruebas22/laravel/app/Http/
                    Livewire/Pedidos/ConfigFormu/Admin.php:" . "<br><br><br><pre>" . $th . "</pre>";
                
                $contenido = [
                    'titulo_para_email' => $titulo_para_email,
                    'cuerpo_para_email' => $cuerpo_para_email,
                    'titulo_para_usuario' => $titulo_para_usuario,
                    'cuerpo_para_usuario' => $cuerpo_para_usuario
                ]; 
                \Mail::to($destinatario)->send(new \App\Mail\enviosCatch($asunto , $contenido));
                // mensaje en la ventana del navegador:
                session()->flash('message_titulo', $titulo_para_usuario);
                session()->flash('message_cuerpo', $cuerpo_para_usuario);
                // Regresar al gridview de tipos de producto:
                return redirect()->to('/generar-formu-admin');                
            }
        }else{
            // debe devolver el schema::table: 
            Schema::dropIfExists($nueva_tabla);  
            // mensaje en la ventana del navegador: (el email fue enviado en el catch)
            session()->flash('message_titulo', $titulo_para_usuario);
            session()->flash('message_cuerpo', $cuerpo_para_usuario);            
            // Regresar al gridview de tipos de producto:
            return redirect()->to('/generar-formu-admin');             

            // dd('error al crear tabla formu__......');
        }
    }  // fin submit_admin()

    public function llamar_modificar_nombre_largo(){
        // para llamar el componente livewire que permite modificar el nombre largo
        // de un tipo de producto:
// dd('en llamar modif....');        
        // return redirect(url('modificar-nombre-largo' , [
        //     'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
        //     'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
        // ]));
    }     

} // fin class Admin
