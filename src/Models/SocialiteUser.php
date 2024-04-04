<?php

namespace Wjbecker\FilamentConnectify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wjbecker\FilamentConnectify\Facades\FilamentConnectify;
use Wjbecker\FilamentConnectify\FilamentConnectifyPlugin;

class SocialiteUser extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'name',
        'email',
        'phone',
        'token',
        'refresh_token',
        'expires_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(FilamentConnectifyPlugin::get()->getUserModel());
    }
}
