<?php

namespace App\Policies;

use App\Policies\Concerns\HandlesAdminOnlyAuthorization;

class UserPolicy
{
    use HandlesAdminOnlyAuthorization;
}
