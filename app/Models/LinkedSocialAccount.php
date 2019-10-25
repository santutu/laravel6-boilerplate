<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LinkedSocialAccount
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider_id
 * @property string $provider_name
 * @property string|null $avatar
 * @property string|null $refresh_token
 * @property string|null $access_token
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkedSocialAccount whereUserId($value)
 * @mixin \Eloquent
 */
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
