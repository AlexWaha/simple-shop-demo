<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReport;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReportCommand extends Command
{
    protected $signature = 'sales:daily-report';

    protected $description = 'Send daily sales report to admin';

    public function handle(): int
    {
        $today = Carbon::today();

        $orders = Order::with('items')
            ->where('status', 'completed')
            ->whereDate('created_at', $today)
            ->get();

        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();

        // Aggregate products sold
        $productsSold = $orders->flatMap->items
            ->groupBy('product_id')
            ->map(fn ($items) => [
                'name' => $items->first()->product_name,
                'quantity' => $items->sum('quantity'),
                'revenue' => $items->sum('total'),
            ])
            ->sortByDesc('quantity')
            ->values();

        $admin = User::where('is_admin', true)->first();

        if ($admin) {
            Mail::to($admin)->queue(new DailySalesReport(
                date: $today,
                totalOrders: $totalOrders,
                totalRevenue: $totalRevenue,
                productsSold: $productsSold,
            ));

            $this->info("Daily sales report sent to {$admin->email}");
        } else {
            $this->warn('No admin user found.');
        }

        return Command::SUCCESS;
    }
}
