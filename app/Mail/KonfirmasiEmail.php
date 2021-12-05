<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class KonfirmasiEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $code_expired;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $code_expired)
    {
        $this->link = $link;
        $this->code_expired = $code_expired;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.linkResetPWD');
        return $this->from('noreply@keuanganku.my.id')
                    ->subject('Konfirmasi')
                    ->view('emails.konfirmasiEmail');
    }
}
