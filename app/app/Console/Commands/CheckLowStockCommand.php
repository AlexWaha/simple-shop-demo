<?php

namespace App\Console\Commands;

use App\Mail\LowStockNotification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLowStockCommand extends Command
{
    protected $signature = 'stock:check-low';

    protected $description = 'Check for low stock products and notify admin';

    public function handle(): int
    {
        $threshold = config('shop.low_stock_threshold', 5);

        $lowStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity')
            ->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('No low stock products found.');

            return self::SUCCESS;
        }

        $admin = User::where('is_admin', true)->first();

        if (! $admin) {
            $this->error('No admin user found.');

            return self::FAILURE;
        }

        Mail::to($admin->email)->send(new LowStockNotification($lowStockProducts));

        $this->info("Low stock notification sent for {$lowStockProducts->count()} product(s) to {$admin->email}");

        return self::SUCCESS;
    }
}
