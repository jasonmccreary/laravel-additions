<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;

class PostController
{
    public function index()
    {
        Gate::authorize('viewAny', Post::class);
    }

    public function ability()
    {
        Gate::authorize('passAbility', Post::class);
    }

    public function user()
    {
        Gate::authorize('user', Post::class);
    }

    public function show(Post $post)
    {
        Gate::authorize('view', $post);
    }

    public function modelless()
    {
        Gate::authorize('modelless', Post::class);
    }

    public function arguments(Post $post)
    {
        Gate::authorize('arguments', [$post, true]);
    }

    public function other(User $user)
    {
        Gate::authorize('other', [Post::class, $user, true]);
    }

    public function explicit(Request $request)
    {
        Gate::authorize('explicit-ability', [Post::class, null, $request->boolean('pass')]);
    }

    public function noFallback()
    {
        Gate::authorize('no-fallback');
    }
}
