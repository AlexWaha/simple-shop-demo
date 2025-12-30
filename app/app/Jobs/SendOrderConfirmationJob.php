<?php

namespace App\Jobs;

use App\Mail\NewOrderNotification;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function handle(): void
    {
        $this->order->load('user', 'items');

        // Send confirmation to customer
        Mail::to($this->order->user)->send(new OrderConfirmation($this->order));

        // Send notification to admins
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            Mail::to($admin)->send(new NewOrderNotification($this->order));
        }
    }
}
