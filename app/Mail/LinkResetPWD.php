<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LinkResetPWD extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $email;
    public $username;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $email, $username)
    {
        $this->link = $link;
        $this->email = $email;
        $this->username = $username;
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
                    ->subject('Reset Password')
                    ->view('emails.linkResetPWD');
    }
}
