<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\Recaudo;
use App\Models\Consignacion;
use Illuminate\Support\Facades\Auth;


class CrearRecaudos extends Component
{
    use WithFileUploads;

    public $categoria;
    public $cliente_id;
    public $consignacion_id;
    public $valor;
    public $tipo = 2;
    public $fec_recaudo;
    public $obs;
    public $foto;
    public $contador_foto;
    public $boton_eliminar_foto = false;
    public $mensaje;
    public $mensaje_error;

    protected $rules = [
        'categoria' => 'required',
        'cliente_id' => 'required',
        // 'consignacion_id' => 'required',
        // 'valor' => 'numeric|required|min:1',
        'tipo' => 'required',
        // 'fec_recaudo' => 'required',
        'foto' => 'nullable|image|max:4096',   // 4mb max y no es obligatoria
    ];

    protected $messages = [
        'foto.image' => 'Solo se pueden subir imágenes.',
        'foto.max' => 'La imagen no puede tener mas de 4 megas.',
    ];    

    public function render()
    {
// print_r(Auth::user()->id);
// print_r(Auth::user()->roles->pluck('name')->first());
// exit;

        // Para el combo de clientes: 
        if(Auth::user()->roles->pluck('name')->first() == "comer"){
            $arr_params =[];
            $sql1 = "select id, nom_cliente 
                from clientes 
                where comercial_id = :comercial_id 
                    and estado = 3
                order by nom_cliente";
            $arr_params = [
                'comercial_id' => Auth::user()->id
            ];            
            $clientes = collect(DB::select($sql1, $arr_params));
        }else{
            $sql2 = "select cli.id id, cli.nom_cliente, usu.name comercial
                from clientes cli
                    left join users usu on usu.id=cli.comercial_id
                where estado = 3
                order by cli.nom_cliente";
            $clientes = collect(DB::select($sql2));
        }

        // Para el combo de consignaciones: 
        $sql3 = "SELECT id,fecha,valor,referencia FROM `consignaciones` WHERE estado=1 order by fecha";
        $consignaciones = collect(DB::select($sql3));
        return view('livewire.crear-recaudos' , compact('clientes') , compact('consignaciones'));
    }

    public function submit($fec_recaudo){
        // llamado cuando se hace el submit al formulario que crea un 
        // nuevo recaudo, debe hacer todo esto: 
        //     validar en el lado cliente 
        //     validar en el lado del servidor: fecha, valor, consignación_id 
        //     Grabar el recaudo en la tabla recaudos 
        //     Grabar en el hosting la foto del comprobante 
        //     Modificar en la tabla consignaciones, si aplica 
        //     Reiniciar los campos del formulario y mostrar mensaje

        $this->mensaje = "";
        $this->mensaje_error = "";
        $this->fec_recaudo = $fec_recaudo;

        $this->validate();

        // 18abr2021: 
        // Validación en el lado servidor:
        // Validar que si el tipo es efectivo obligatoriamente se haya digitado 
        // valor y fecha, y si el tipo es consignación obligatoriamente se haya 
        // escogido una consignacion_id, la validación correcta/incorrecta 
        // es validada con ayuda de la variable $validar_servidor
        if($this->tipo == 1){
            // validar fecha y valor de recaudo: 
            // la fecha de recaudo puede estar separada con - o con /:
            $arr_fecha_partida = explode('-', $this->fec_recaudo);
            if(count($arr_fecha_partida) !== 3){
                $arr_fecha_partida = explode('/', $this->fec_recaudo);
            }
            // los parametros que recibe checkdate son: mes, dia, año; en ese orden.
            if(count($arr_fecha_partida) == 3 && checkdate($arr_fecha_partida[1], $arr_fecha_partida[2], $arr_fecha_partida[0])){
                // validar valor: 
                if(is_numeric($this->valor)){
                    $validar_servidor = true; 
                }else{
                    $validar_servidor = false;
                    $this->mensaje_error = "Debe digitar un valor correcto.";
                }
            }else{
                $validar_servidor = false;
                $this->mensaje_error = "Debe escoger o digitar una fecha de recaudo correcta (aaaa-mm-dd).";
            }
        }else{
            // debe buscar el consignación_id en la tabla consignaciones: 
            $arr_params =[];
            $sql4 = "select fecha,valor 
                from consignaciones 
                where id=:consignacion_id and estado=1 ";
            $arr_params = [
                'consignacion_id' => $this->consignacion_id,
            ];            
            $consignacion = collect(DB::select($sql4, $arr_params));
            if(count($consignacion) >= 1){
                $validar_servidor = true;
                // $this->fec_recaudo = $consignacion[0]['fecha'];
                $this->fec_recaudo = $consignacion[0]->fecha;
                $this->valor = $consignacion[0]->valor;
            }else{
                $validar_servidor = false;
                $this->mensaje_error = "Debe escoger una consignación.";
            }
        }

        if($validar_servidor){
            // Grabación en la tabla recaudos:
            $estado = 1;
            $nuevo = Recaudo::create([
                'categoria' => $this->categoria,
                'cliente_id' => $this->cliente_id,
                'fec_pago' => $this->fec_recaudo,
                'valor' => $this->valor,
                'tipo' => $this->tipo,
                'obs' => $this->obs,
                'estado' => $estado,
                'user_id' => Auth::user()->id
            ]);
            $ultimo_id = $nuevo->id;

            // creación de la foto del comprobante:
            if($this->foto){
                $this->foto->store('public/comptes/' . $ultimo_id);
            }

            // modificación en la tabla consignaciones: (cambio de estado si 
            // se escogió alguna consignación de esta tabla):
            if($this->tipo == 2){
                $consignacion_modificar = Consignacion::find($this->consignacion_id);

                $consignacion_modificar->estado = 2;
                $consignacion_modificar->recaudo_id = $ultimo_id;
                $consignacion_modificar->created_asigno_at = date('Y-m-d h:i:s');
                $consignacion_modificar->user_asigno_id = Auth::user()->id;
        
                $consignacion_modificar->save();                
            }

            // Reiniciar formulario:
            $cliente = Cliente::find($this->cliente_id);
            $this->mensaje = "Acaba de ser grabado un recaudo del cliente " . $cliente->nom_cliente . " por valor de $" . number_format($this->valor,0);
            $this->reset(['categoria' , 'cliente_id' ,  'valor' , 'tipo' , 'obs' , 'consignacion_id' ]);
            $this->reset_adjunto();
        }
        // si no entró al anterior if, al hacer el render() enviará el $this->mensaje_error
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
