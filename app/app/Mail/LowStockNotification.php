<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class LowStockNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $products
    ) {}

    public function envelope(): Envelope
    {
        $count = $this->products->count();

        return new Envelope(
            subject: "Low Stock Alert: {$count} product(s) need attention",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.low-stock-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
