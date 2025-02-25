<?php

namespace App\Console;

use App\Models\Order;
use App\Jobs\ExpireOrderJob;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Every minute, check for orders that have passed their payment deadline
        $schedule->call(function () {
            $expiredOrders = Order::where('status', 'pending')
                                ->where('payment_deadline', '<', Carbon::now())
                                ->get();
            
            foreach ($expiredOrders as $order) {
                ExpireOrderJob::dispatch($order);
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}