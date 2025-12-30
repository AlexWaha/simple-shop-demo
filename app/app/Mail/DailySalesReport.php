<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailySalesReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Carbon $date,
        public int $totalOrders,
        public float $totalRevenue,
        public Collection $productsSold
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Daily Sales Report - {$this->date->format('M d, Y')}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.daily-sales-report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
