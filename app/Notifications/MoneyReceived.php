<?php

namespace App\Notifications;

use App\Models\Transaction;
use App\Notifications\Channels\PayServiceChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MoneyReceived extends Notification implements ShouldQueue
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

        $this->afterCommit = true;
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
        return [PayServiceChannel::class];
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
