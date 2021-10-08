<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'owner_id',
        'ammount',
    ];

    /**
     * Subtract the specified $value from the wallet.
     *
     * @param  float  $value
     */
    public function subtract(float $value) : void
    {
        $this->attributes['ammount'] -= $value;
        $this->save();
    }

    /**
     * Add the specified $value from the wallet.
     *
     * @param  float  $value
     */
    public function add(float $value) : void
    {
        $this->attributes['ammount'] += $value;
        $this->save();
    }

    /**
     * The owner of this wallet.
     *
     * @return BelongsTo
     */
    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
