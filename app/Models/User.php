<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $with = ['individualProfile', 'companyProfile', 'wallet'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Returns the wallet's balance.
     *
     * @return float
     */
    public function balance() : float
    {
        return $this->wallet->ammount;
    }

    /**
     * If this user is a company, returns the related profile.
     *
     * @return HasOne
     */
    public function companyProfile() : HasOne
    {
        return $this->hasOne(CompanyUser::class, 'user_id');
    }

    /**
     * If this user is individual, returns the related profile.
     *
     * @return HasOne
     */
    public function individualProfile() : HasOne
    {
        return $this->hasOne(IndividualUser::class, 'user_id');
    }

    /**
     * This user's wallet.
     *
     * @return HasOne
     */
    public function wallet() : HasOne
    {
        return $this->hasOne(Wallet::class, 'owner_id');
    }
}
