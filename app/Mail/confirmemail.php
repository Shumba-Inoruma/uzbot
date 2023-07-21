<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class confirmemail extends Mailable
{
    use Queueable, SerializesModels;
    private $data=[];

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data=$data;
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmemail',
        );
    }

    /**
     * Get the message content definition.
     */
  

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
     public function build()

    {
  

        return $this->from("chirovemunyaradzi@gmail.com",'Africom Customer Email')
                    ->subject($this->data['subject'])
                    ->view('email.index')->with('data',$this->data);
    }
}

