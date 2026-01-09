<?php
namespace App\Jobs;
use App\Models\User;
use App\Models\Validations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $validation, $user, $email;
    
    //Create a new job instance.
    public function __construct(Validations $validation, User $user, $email)
    {
        $this->user = $user;
        $this->validation = $validation;
        $this->email = $email;
    }

    //Execute the job.
    public function handle()
    {
        Mail::to($this->user->email)->send($this->email);
    }
}