<?php

// app/Policies/PollPolicy.php
namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    public function create(User $user): bool { return true; } // cualquiera logueado
    public function update(User $user, Poll $poll): bool { return $poll->user_id === $user->id; }
    public function delete(User $user, Poll $poll): bool { return $poll->user_id === $user->id; }
    public function close(User $user, Poll $poll): bool { return $poll->user_id === $user->id; }
}
