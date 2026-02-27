<?php

namespace App\Domains\Warehouse\Notifications;

use App\Domains\Warehouse\Models\Stock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Stock $stock) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Stock Alert')
            ->line("Low stock detected for item #{$this->stock->inventory_item_id}")
            ->line("Warehouse: #{$this->stock->warehouse_id}")
            ->line("Current quantity: {$this->stock->quantity}")
            ->action('View Inventory', url('/'))
            ->line('Please restock this item as soon as possible.');
    }
}
