<?php

namespace App\Policies;

use App\Policies\Concerns\HandlesAdminOnlyAuthorization;

class SiteSettingPolicy
{
    use HandlesAdminOnlyAuthorization;
}
