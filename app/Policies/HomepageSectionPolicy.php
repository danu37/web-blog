<?php

namespace App\Policies;

use App\Policies\Concerns\HandlesAdminOnlyAuthorization;

class HomepageSectionPolicy
{
    use HandlesAdminOnlyAuthorization;
}
