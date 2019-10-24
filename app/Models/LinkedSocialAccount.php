<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
    const table = 'linked_social_accounts';
    protected $table = self::table;

    protected $fillable = ['provider_name', 'provider_id', 'avatar', 'refresh_token', 'access_token', 'expired_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
