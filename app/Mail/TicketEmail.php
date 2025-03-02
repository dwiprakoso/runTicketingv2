<?php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $ticketPdf;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        // Generate PDF ticket
        $pdf = PDF::loadView('emails.ticket-pdf', ['order' => $this->order]);
        
        return $this->subject('Tiket Event Anda')
                   ->view('emails.ticket');
        // return $this->subject('Tiket Event Anda')
        //            ->view('emails.ticket')
        //            ->attachData($pdf->output(), 'ticket.pdf', [
        //                'mime' => 'application/pdf',
        //            ]);
    }
}