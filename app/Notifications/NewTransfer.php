<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTransfer extends Notification
{
    use Queueable;

    private Transaction $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transaction->load('payerWallet', 'payeeWallet');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable) : array
    {
        return ['payservice'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable) : array
    {
        return [
            'from'    => $this->transaction->payer()->name,
            'to'      => $this->transaction->payee()->name,
            'ammount' => $this->transaction->ammount,
        ];
    }
}
