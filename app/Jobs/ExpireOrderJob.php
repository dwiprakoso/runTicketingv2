<?php
namespace App\Jobs;

use App\Mail\OrderRejected;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ExpireOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        // Check if order is still pending
        if ($this->order->status === 'pending') {
            $this->order->status = 'expired';
            $this->order->save();
            
            if ($this->order->payment) {
                $this->order->payment->status = 'rejected';
                $this->order->payment->save();
            }
            
            // Send expired notification
            Mail::to($this->order->user->email)
                ->send(new OrderRejected($this->order));
        }
    }
}