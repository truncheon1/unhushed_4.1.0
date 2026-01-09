<?php
namespace App\Mail;
use App\Models\User;
use App\Models\Validations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; //this wasn't in here, but IS in the other transactional emails.
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class PasswordEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $validation_string, $name;

    //Create a new message instance.
    public function __construct(Validations $validation, User $user)
    {
        $this->validation_string = $validation->validation_string;
        $this->name = $user->name;
    }

    //Build the message.
    public function build()
    {
        return $this->view('emails.password_email')->with(
            [
                'name'=>$this->name,
                'validation_string'=>$this->validation_string
            ]
        );
    }
}
