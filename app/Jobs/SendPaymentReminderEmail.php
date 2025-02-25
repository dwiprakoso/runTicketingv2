<?php
// app/Jobs/SendPaymentReminderEmail.php
namespace App\Jobs;

use App\Mail\PaymentReminder;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        // Check if order is still pending and no payment proof yet
        if ($this->order->status === 'pending' && 
            (!$this->order->payment || !$this->order->payment->proof_image)) {
            
            Mail::to($this->order->user->email)
                ->send(new PaymentReminder($this->order));
        }
    }
}