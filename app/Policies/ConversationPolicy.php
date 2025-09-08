<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the conversation.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->user_id === $user->id || $conversation->photographer_id === $user->id;
    }

    /**
     * Determine whether the user can create conversations.
     */
    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    /**
     * Determine whether the user can update the conversation.
     */
    public function update(User $user, Conversation $conversation): bool
    {
        return $conversation->user_id === $user->id || $conversation->photographer_id === $user->id;
    }

    /**
     * Determine whether the user can delete the conversation.
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        return $conversation->user_id === $user->id || $conversation->photographer_id === $user->id;
    }
}