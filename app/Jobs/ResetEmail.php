<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\UserPasswordRest;
use Illuminate\Support\Str;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Facade\Ignition\DumpRecorder\Dump;

class ResetEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $email;
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Generate Token
        $token = Str::random(60);

        // Saving Data to Password Reset Table
        UserPasswordRest::create([
            'email' => $this->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Sending EMail with Password Reset View
        $checkemail =  Mail::send('adminreset', ['token' => $token], function (Message $message) {
            $message->subject('Reset Your Password');
            $message->to($this->email);
        });
    }
}
