<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'cnpj',
        'trading_name',
    ];

    /**
     * The details of this user.
     *
     * @return BelongsTo
     */
    public function profile() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
