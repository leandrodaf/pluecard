<?php

namespace App\Models;

use App\Models\Payment\Card;
use App\Models\Payment\Payer;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, HasRoles, FullTextSearch;

    protected $fillable = [
        'name',
        'email',
        'password',
        'accept_terms',
        'newsletter',
        'discount_coupons',
        'confirmation_email',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'accept_terms' => 'boolean',
        'newsletter' => 'boolean',
        'discount_coupons' => 'boolean',
        'confirmation_email' => 'boolean',
    ];

    protected $searchable = [
        'name',
        'email',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function confirmationAccount(): HasOne
    {
        return $this->hasOne(ConfirmationAccount::class, 'user_id', 'id');
    }

    public function resetPasswords(): HasMany
    {
        return $this->hasMany(ResetPassword::class, 'user_id', 'id');
    }

    public function socialAuths(): HasMany
    {
        return $this->hasMany(SocialAuths::class, 'user_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'user_id', 'id');
    }

    public function payers(): HasMany
    {
        return $this->hasMany(Payer::class, 'user_id', 'id');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Administrator');
    }
}
