<?php

namespace Tests\Feature\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use JMac\Additions\Traits\WithFallback;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;
use Workbench\App\Policies\PostPolicy;
use Workbench\App\Policies\TestPolicy;

final class WithFallbackTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_uses_the_trait(): void
    {
        $this->assertContains(WithFallback::class, class_uses(PostPolicy::class));
    }

    #[Test]
    public function it_throws_exception_when_fallback_method_is_missing(): void
    {
        Gate::define('no-fallback', [TestPolicy::class, 'noFallback']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You must define a fallback method when using the WithFallback trait');

        $user = User::factory()->create();

        $this->withoutExceptionHandling();

        $this->actingAs($user)->get(route('posts.no-fallback'));
    }

    #[Test]
    public function it_fails_by_default(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.index'));

        $response->assertForbidden();
    }

    #[Test]
    public function it_passed_the_snake_case_ability_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.ability'));

        $response->assertOk();
    }

    #[Test]
    public function it_passes_the_authenticated_user(): void
    {
        $user = User::factory()->create([
            'email' => 'test@pass.me',
        ]);

        $response = $this->actingAs($user)->get(route('posts.user'));

        $response->assertOk();
    }

    #[Test]
    public function it_fails_when_passed_user_when_unexpected_value(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.user'));

        $response->assertForbidden();
    }

    #[Test]
    public function it_works_without_model(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('posts.modelless'));

        $response->assertOk();
    }

    #[Test]
    public function it_passes_model(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'title' => 'I Passed!',
        ]);

        $response = $this->actingAs($user)
            ->get(route('posts.model', $post));

        $response->assertOk();
    }

    #[Test]
    public function it_fails_model_when_unexpected_value(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('posts.model', $post));

        $response->assertForbidden();
    }

    #[Test]
    public function it_passes_arguments(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'title' => 'I Passed!',
        ]);

        $response = $this->actingAs($user)
            ->get(route('posts.arguments', $post));

        $response->assertOk();
    }

    #[Test]
    public function it_fails_arguments_when_unexpected_value(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('posts.arguments', $post));

        $response->assertForbidden();
    }

    #[Test]
    public function it_passes_arguments_with_other_model(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create([
            'email' => 'test@pass.me',
        ]);

        $response = $this->actingAs($user)
            ->get(route('posts.other', $other));

        $response->assertOk();
    }

    #[Test]
    public function it_fails_arguments_with_other_model_when_unexpected_value(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('posts.other', $other));

        $response->assertForbidden();
    }

    #[Test]
    public function it_still_uses_explicit_method(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('posts.explicit', ['pass' => true]));

        $response->assertOk();

        $response = $this->actingAs($user)
            ->get(route('posts.explicit', ['pass' => false]));

        $response->assertForbidden();
    }
}
