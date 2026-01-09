<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    //Create a new message instance.
    public function __construct()
    {
        //
    }

    //Build the message.
    public function build()
    {
        return $this->view('emails.contact_email');
    }
}
