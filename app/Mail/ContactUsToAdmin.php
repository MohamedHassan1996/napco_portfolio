<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ContactUs\ContactUs;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\ContactUs\ContactUsMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUsToAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $contactUs;
    public $contactUsMessage;
    /**
     * Create a new message instance.
     */
    public function __construct(ContactUsMessage $contactUsMessage ,ContactUs $contactUs)
    {
        $this->contactUsMessage = $contactUsMessage;
        $this->contactUs = $contactUs;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject($this->contactUs->subject)
                    ->view('Emails.contact_us_to_admin')
                    ->with('message', $this->contactUsMessage->message);
    }




}
