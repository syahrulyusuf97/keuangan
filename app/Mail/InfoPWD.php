<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InfoPWD extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $username;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $username, $password)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.infoPWD');
        return $this->from('noreply@keuanganku.my.id')
                    ->subject('Informasi Akun')
                    ->view('emails.infoPWD');
    }
}
