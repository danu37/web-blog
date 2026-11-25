<?php

namespace App\Policies;

use App\Policies\Concerns\HandlesEditorialAuthorization;

class MediaPolicy
{
    use HandlesEditorialAuthorization;
}
