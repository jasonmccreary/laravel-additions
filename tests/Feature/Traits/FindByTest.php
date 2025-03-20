<?php

namespace Tests\Feature\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Workbench\App\Models\Post;

final class FindByTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_null_when_nothing_is_found(): void
    {
        $this->assertNull(Post::findByTitle('does not exist'));
    }

    #[Test]
    public function it_returns_empty_collect_when_nothing_is_found_for_set(): void
    {
        $result = Post::findByTitle(['not found', 'also not found']);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    #[Test]
    public function it_returns_a_single_model_when_found(): void
    {
        $post = Post::factory()->create(['title' => 'My Post']);

        $result = Post::findByTitle('My Post');

        $this->assertInstanceOf(Post::class, $result);
        $this->assertTrue($result->is($post));
    }

    #[Test]
    public function it_returns_a_collection_when_found(): void
    {
        $post1 = Post::factory()->create(['title' => 'Title 1']);
        $post2 = Post::factory()->create(['title' => 'Title 2']);

        $result = Post::findByTitle(['Title 1', 'Title 2']);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertTrue($result->first()->is($post1));
        $this->assertTrue($result->last()->is($post2));
    }

    #[Test]
    public function it_returns_selected_column(): void
    {
        $post = Post::factory()->create(['title' => 'My Post']);

        $result = Post::findByTitle('My Post', ['id']);

        $this->assertInstanceOf(Post::class, $result);
        $this->assertTrue($result->is($post));
        $this->assertSame(['id' => $post->id], $result->toArray());
    }

    #[Test]
    public function it_returns_collection_of_selected_column(): void
    {
        Post::factory()->create(['title' => 'Title 1']);
        Post::factory()->create(['title' => 'Title 2']);

        $result = Post::findByTitle(['Title 1', 'Title 2'], 'title');

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertSame(['title' => 'Title 1'], $result->first()->toArray());
        $this->assertSame(['title' => 'Title 2'], $result->last()->toArray());
    }

    #[Test]
    public function it_returns_null_when_column_does_not_exist(): void
    {
        $this->assertNull(Post::findByUnknownColumn('uh oh'));
    }

    #[Test]
    public function it_returns_empty_collect_when_column_does_not_exist(): void
    {
        $result = Post::findByUnknownColumn(['no', 'nope']);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }
}
