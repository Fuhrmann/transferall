<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndividualUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'cpf',
        'rg',
        'date_of_birthday',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birthday' => 'datetime',
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
