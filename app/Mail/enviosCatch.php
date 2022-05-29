<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class enviosCatch extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;
    public $contenido;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($asunto , $contenido)
    {
        $this->asunto = $asunto;
        $this->contenido = $contenido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            // ->from(['address' => 'envios@tavohen.com' , 'name' => 'Envios desde los catch'])
            ->subject($this->asunto)
            ->view('emails.envios-catch')
            // -text('emails.envios-catch-texto')  
            // ->with()
            // ->attach()
            // ->reply_to()   
            ;
    }
}
