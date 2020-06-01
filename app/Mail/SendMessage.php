<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $subject;
    public $pesan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $subject, $pesan)
    {
        $this->name = urldecode($name);
        $this->email = urldecode($email);
        $this->subject = urldecode($subject);
        $this->pesan = urldecode($pesan);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.linkResetPWD');
        return $this->from($this->email)
                    ->subject($this->subject)
                    ->view('emails.sendMessage');
    }
}
