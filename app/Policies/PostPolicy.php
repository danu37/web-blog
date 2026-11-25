<?php

namespace App\Policies;

use App\Policies\Concerns\HandlesEditorialAuthorization;

class PostPolicy
{
    use HandlesEditorialAuthorization;
}
