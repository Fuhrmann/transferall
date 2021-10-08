<?php

namespace App\Models;

use App\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
