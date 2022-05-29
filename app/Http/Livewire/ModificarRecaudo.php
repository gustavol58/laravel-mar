<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Models\Recaudo;
use App\Models\RecaudosHistoriaModificados;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class ModificarRecaudo extends Component
{
    use WithFileUploads;

    // contiene el id del recaudo que va a ser modificado:
    public $recaudo_id;

    // contiene los valores que tenia el recaudo antes de ser modificado, se 
    // usa en mount() y además en submit() para llenar 
    // la tabla recaudos_historia_modificados
    public $recaudo_original;

    // los campos que pueden ser modificados por el usuario: 
    public $categoria;
    public $cliente_model_id;
    public $valor;
    public $tipo;
    public $fec_recaudo;
    public $estado;    
    public $estado_texto;    
    public $valor_asentado;    
    public $obs;
    public $foto;
    public $contador_foto;
    public $boton_eliminar_foto = false;
    public $mensaje;
    public $foto_existe_nombre;

    // más adelante se le agregará una nueva regla a $rules:
    protected $rules = [
        'categoria' => 'required|integer|between:1,2',
        'cliente_model_id' => 'required',
        'valor' => 'numeric|required|min:1',
        'tipo' => 'required|integer|between:1,2',
        'fec_recaudo' => 'required',
        'foto' => 'nullable|image|max:4096',   // 4mb max y no es obligatoria
    ];

    protected $messages = [
        'categoria.between' => 'Debe escoger una categoria',        
        'tipo.between' => 'Debe escoger el tipo de pago',        
        'foto.image' => 'Solo se pueden subir imágenes.',
        'foto.max' => 'La imagen no puede tener mas de 4 megas.',
    ]; 

    public function rules(){
        // para agregar una regla solo si el recaudo tiene estado Asentado:
        $rules = $this->rules;
        if($this->estado == 3){
            $rules['valor_asentado'] = 'numeric|required|min:1|';
        }        
        return $rules;        
    }

    public function mount($id){
        $this->recaudo_id = $id;

        $recaudo = Recaudo::find($this->recaudo_id);
        $this->recaudo_original = $recaudo;

        $this->categoria = $recaudo->categoria;
        $this->cliente_model_id = $recaudo->cliente_id;
        $this->valor = $recaudo->valor;
        $this->tipo = $recaudo->tipo;
        $this->fec_recaudo = $recaudo->fec_pago;
        $this->estado = $recaudo->estado;        
        $this->valor_asentado = $recaudo->valor_asiento;        
        $this->obs = $recaudo->obs;
        switch ($this->estado) {
            case 1:
                $this->estado_texto = 'Nuevo';
                break;
            case 2:
                $this->estado_texto = 'Aprobado';
                break;
            case 3:
                $this->estado_texto = 'Asentado';
                break;
            case 4:
                $this->estado_texto = 'Anulado';
                break;
        }        

        //verificamos si el recaudo tiene foto asignada:
        $this->foto_existe_nombre = '';
        if (Storage::disk('public')->exists('comptes/'.$this->recaudo_id)){
            // en el locahost public_path() quedará con /var/www/html/markka/public 
            // pero en un hosting compartido quedará con /home/tavohenc/markka_pr/public y debido 
            // a que en el hosting compartido la carpeta publica va en un lado (public_html/markka) y los demás 
            // archivos van en otro (markka), entonces lo que sirve para el localhost no sirve para el hosting 
            //      para el localhost sirve /var/www/html/markka/public 
            //      para el hosting compartido sirve /home/tavohenc/public_html/markka_pr
            // Por lo tanto al  SUBIR AL  HOSTING asegurarse de quitar el comentario a la siguiente instrucción:
            // PARA EL LOCALHOST Y PARA EL HOSTING COMPARTIDO: Dejar sin comentariar lo siguiente:
            // $public_path = public_path();
            // La siguiente instrucción: Comentariada para el LOCALHOST, Descomentariada para el HOSTING COMPARTIDO:
            //    (notar como se cambia '/markka_pr/public'    por    '/public_html/markka_pr')
            // $public_path = str_replace('/markka_pr/public' , '' , $public_path).'/public_html/markka_pr';

            // 07mar2021
            // Todo lo anterior se simplifico usando constantes laravel:
            $public_path = config('constantes.path_foto_compte');
            $ruta = $public_path.'/storage/comptes/'.$this->recaudo_id.'/';
 
            $filehandle = opendir($ruta);
            while ($file = readdir($filehandle)) {
                if ($file != "." && $file != ".." ) {
                    $foto_existe_nombre_completo = $ruta.$file;
                }
            }
            closedir($filehandle);  
            if($foto_existe_nombre_completo !== ''){
                $arr_foto_existe_nombre_completo = explode('/' , $foto_existe_nombre_completo);
                $this->foto_existe_nombre = 'comptes/' . $this->recaudo_id . '/' . end( $arr_foto_existe_nombre_completo )  ;
            }
        }        
    }

    public function render()
    {
        $sql1 = "select cli.id id, cli.nom_cliente, usu.name comercial
        from clientes cli
            left join users usu on usu.id=cli.comercial_id
        order by cli.nom_cliente";
        $clientes_combo = collect(DB::select($sql1));
        return view('livewire.modificar-recaudo' , compact('clientes_combo') );
    }

    public function submit($fec_recaudo){
        $this->mensaje = "";
        $this->fec_recaudo = $fec_recaudo;

        $this->validate();

        $recaudo = Recaudo::find($this->recaudo_id);

        $recaudo->categoria = $this->categoria;
        $recaudo->cliente_id = $this->cliente_model_id;
        $recaudo->valor = $this->valor;
        $recaudo->tipo = $this->tipo;
        $recaudo->fec_pago = $this->fec_recaudo;
        if(! empty($this->valor_asentado)){
            $recaudo->valor_asiento = $this->valor_asentado;
        }
        $recaudo->obs = $this->obs;
        $recaudo->user_updated_id = Auth::user()->id;

        $recaudo->save();

        // grabar en la tabla recaudos_historia_modificados los valores que 
        // originalmente tenia el recaudo antes de haber grabado las modificaciones: 
        RecaudosHistoriaModificados::create([
            'recaudo_id' => $this->recaudo_original->id,
            'categoria' => $this->recaudo_original->categoria,
            'cliente_id' => $this->recaudo_original->cliente_id,
            'fec_pago' => $this->recaudo_original->fec_pago,
            'valor' => $this->recaudo_original->valor,
            'tipo' => $this->recaudo_original->tipo,
            'obs' => $this->recaudo_original->obs,
            'estado' => $this->recaudo_original->estado,
            'valor_asiento' => $this->recaudo_original->valor_asiento,
            'notas_asiento' => $this->recaudo_original->notas_asiento,
            'user_id' => $this->recaudo_original->user_id,
            'user_asiento_id' => $this->recaudo_original->user_asiento_id,
            'user_updated_id' => $this->recaudo_original->user_updated_id,
            'user_aprobo_id' => $this->recaudo_original->user_aprobo_id,
            'created_at' => $this->recaudo_original->created_at,
            'updated_at' => $this->recaudo_original->updated_at,
            'created_aprobo_at' => $this->recaudo_original->created_aprobo_at,
            'created_asiento_at' => $this->recaudo_original->created_asiento_at,
        ]);

        // creación de la foto del comprobante:
        if($this->foto){
            // solamente debe una imagen por carpeta, por eso es necesario borrar las imágenes que 
            // puedan haber antes de subir la nueva, para eso hay que borrar toda la carpeta,
            // al fin y al cabo, después del if será nuevamente creada:
            if (Storage::disk('public')->exists('comptes/'.$this->recaudo_id)){
                Storage::disk('public')->deleteDirectory('comptes/'.$this->recaudo_id);
            }

            $this->foto->store('public/comptes/' . $this->recaudo_id);
        }

        // $cliente = Cliente::find($this->cliente_model_id);
        // $this->mensaje = "Acaba de ser modificado el recaudo número " . $this->recaudo_id .  " del cliente " . $cliente->nom_cliente . " por valor de $" . number_format($this->valor,0);
        $this->reset(['categoria' , 'cliente_model_id' ,  'valor' , 'valor_asentado', 'tipo' , 'obs' ]);
        $this->reset_adjunto();
        return redirect()->route('ver-recaudos');
        

    }  
    
    public function reset_adjunto(){
        $this->foto = null;
        $this->contador_foto++;
    }

    public function hydrate()
    {
        $errors = $this->getErrorBag();
        if($errors->has('foto')){
            $this->resetValidation('foto');
            $this->reset_adjunto();
        }
    }    
    
}
