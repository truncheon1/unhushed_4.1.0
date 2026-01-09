<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $validation_string, $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Models\Validations $validation, \App\Models\User $user)
    {
        $this->validation_string = $validation->validation_string;
        $this->name = $user->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.validation_email')->with(
                [
                    'name'=>$this->name,
                    'validation_string'=>$this->validation_string
                ]
                );
    }
}
