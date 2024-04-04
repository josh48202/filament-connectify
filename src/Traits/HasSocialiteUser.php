<?php

namespace Wjbecker\FilamentConnectify\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Wjbecker\FilamentConnectify\Models\SocialiteUser;

trait HasSocialiteUser
{
    public function socialiteUser(): HasOne
    {
        return $this->hasOne(SocialiteUser::class);
    }
}
