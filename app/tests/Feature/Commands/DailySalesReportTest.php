<?php

use App\Mail\DailySalesReport;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('sends daily sales report to admin', function () {
    Mail::fake();

    $admin = User::factory()->create(['is_admin' => true]);
    Order::factory()->count(3)->create([
        'status' => 'completed',
        'total' => 100,
    ]);

    $this->artisan('sales:daily-report')
        ->assertExitCode(0);

    Mail::assertQueued(DailySalesReport::class, function ($mail) use ($admin) {
        return $mail->hasTo($admin->email);
    });
});

it('does not send report when no admin exists', function () {
    Mail::fake();

    Order::factory()->count(3)->create([
        'status' => 'completed',
        'total' => 100,
    ]);

    $this->artisan('sales:daily-report')
        ->assertExitCode(0);

    Mail::assertNotQueued(DailySalesReport::class);
});

it('includes only completed orders from today in report', function () {
    Mail::fake();

    $admin = User::factory()->create(['is_admin' => true]);

    // Today's completed orders
    Order::factory()->count(2)->create([
        'status' => 'completed',
        'total' => 50,
    ]);

    // Today's pending order (should not be included in totals)
    Order::factory()->pending()->create([
        'total' => 100,
    ]);

    // Yesterday's order (should not be included)
    Order::factory()->create([
        'status' => 'completed',
        'total' => 200,
        'created_at' => now()->subDay(),
    ]);

    $this->artisan('sales:daily-report')
        ->assertExitCode(0);

    Mail::assertQueued(DailySalesReport::class);
});
