<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Review;

class ReviewThankYou extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $reviewable;

    public function __construct(Review $review, $reviewable)
    {
        $this->review = $review;
        $this->reviewable = $reviewable;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('reviews.email_subject'),
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'emails.review-thank-you',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
