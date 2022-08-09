<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParticipantUpdateRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Participant
     *
     * @var Participant
     */
    public $participant;

    /**
     * Email subject
     */
    public $subject;

    /**
     * Update link
     */
    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, $subject, $link)
    {
        $this->participant = $participant;
        $this->subject = $subject;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('emails.updateregistration');
    }
}
