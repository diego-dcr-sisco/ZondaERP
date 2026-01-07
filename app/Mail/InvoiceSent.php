<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InvoiceSent extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $order;
    public $contract;

    public function __construct($invoice, $order = null, $contract = null)
    {
        $this->invoice = $invoice;
        $this->order = $order;
        $this->contract = $contract;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura #' . $this->invoice->id . ' - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'invoices.mails.invoice-sent',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        $tenantStorage = Storage::disk('public');
        
        // Si existe el PDF de la factura, adjuntarlo
        if ($this->invoice->pdf_path && file_exists($tenantStorage->path($this->invoice->pdf_path))) {
            $attachments[] = $tenantStorage->path($this->invoice->pdf_path);
        }
        
        // Si existe el XML, adjuntarlo
        if ($this->invoice->xml_file && file_exists($tenantStorage->path($this->invoice->xml_file))) {
            $attachments[] = $tenantStorage->path($this->invoice->xml_file);
        }     
        
        return $attachments;
    }
}
