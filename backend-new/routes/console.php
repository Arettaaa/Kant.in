<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Order;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto complete orders yang sudah ready lebih dari 1 jam
Schedule::call(function () {
    $orders = Order::where('status', 'ready')
        ->where('updated_at', '<', now()->subHour(1))
        ->get();

    foreach ($orders as $order) {
        $order->update(['status' => 'completed']);
    }
})->everyMinute();