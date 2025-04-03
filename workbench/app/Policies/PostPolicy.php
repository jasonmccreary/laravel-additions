<?php

namespace Workbench\App\Policies;

use JMac\Additions\Traits\WithFallback;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;

class PostPolicy
{
    use WithFallback;

    public function explicitAbility(User $user, ?Post $post, $pass): bool
    {
        return isset($pass) && $pass;
    }

    public function fallback($name, $user, $model, $arguments): ?bool
    {
        if ($name === 'pass-ability' && isset($user) && empty($model) && empty($arguments)) {
            return true;
        } elseif ($name === 'user') {
            return $user?->email === 'test@pass.me';
        } elseif ($name === 'modelless' && empty($model) && empty($arguments)) {
            return true;
        } elseif ($name === 'view') {
            return $model?->title === 'I Passed!';
        } elseif ($name === 'arguments') {
            return $model?->title === 'I Passed!' && count($arguments) === 1 && $arguments[0];
        } elseif ($name === 'other') {
            return is_null($model) && count($arguments) === 2 && $arguments[0]->email === 'test@pass.me' && $arguments[1];
        }

        return false;
    }
}
