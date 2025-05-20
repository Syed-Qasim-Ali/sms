<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $inviteLink;
    public $planpassword;

    public function __construct($user, $inviteLink, $planpassword)
    {
        $this->user = $user;
        $this->inviteLink = $inviteLink;
        $this->planpassword = $planpassword;
    }

    public function build()
    {
        return $this->subject('You are Invited to Join')
            ->view('backend.emails.invite')
            ->with([
                'user' => $this->user,
                'inviteLink' => $this->inviteLink,
                'password' => $this->planpassword,
            ]);
    }
}
