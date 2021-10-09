<?php

namespace App\Models;

use App\Services\Transaction\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Wallet $payerWallet
 * @property Wallet $payeeWallet
 * @property float $ammount
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'wallet_payer_id',
        'wallet_payee_id',
        'ammount',
        'status',
    ];

    public function currentStatusName() : string
    {
        return TransactionStatus::getStatus($this->attributes['status']);
    }

    /**
     * Returns the payee details.
     *
     * @return User
     */
    public function payee() : User
    {
        return $this->payeeWallet->owner;
    }

    /**
     * Returns the payer details.
     *
     * @return User
     */
    public function payer() : User
    {
        return $this->payerWallet->owner;
    }

    /**
     * The payer's wallet.
     *
     * @return BelongsTo
     */
    public function payerWallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_payer_id');
    }

    /**
     * The payee's wallet.
     *
     * @return BelongsTo
     */
    public function payeeWallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_payee_id');
    }
}
