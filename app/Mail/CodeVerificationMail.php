<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodeVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;

    /**
     * Create a new message instance.
     *
     * @param string $verificationCode
     */
    public function __construct($verificationCode, protected User $user)
    {
        $this->verificationCode = $verificationCode;
        $this->user = $user;
    }

    /**

     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Code Verification')
            ->view('email.verification_code')
            ->with([
                'verificationCode' => $this->verificationCode,
                'name' => $this->user->name
            ]);
    }
}
