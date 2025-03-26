<?php

namespace Tests\Feature\Traits;

use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\ValidatedInput;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Workbench\App\Models\Post;

final class SafeSaveTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function create_preserves_default_behavior_when_passed_array(): void
    {
        $this->expectException(MassAssignmentException::class);
        $this->expectExceptionMessage('Add [title] to fillable property to allow mass assignment on [Workbench\App\Models\Post].');

        Post::create(['title' => 'Thou shall not pass.']);
    }

    #[Test]
    public function create_bypasses_mass_assignment_when_pass_validated_input(): void
    {
        $title = fake()->sentence();
        $input = new ValidatedInput(['title' => $title]);

        $post = Post::create($input);

        $this->assertDatabaseHas($post);
        $this->assertSame($title, $post->title);
    }

    #[Test]
    public function update_preserves_default_behavior_when_passed_array(): void
    {
        $this->expectException(MassAssignmentException::class);
        $this->expectExceptionMessage('Add [title] to fillable property to allow mass assignment on [Workbench\App\Models\Post].');

        $post = Post::factory()->create();
        $post->update(['title' => 'Thou shall not pass.']);
    }

    #[Test]
    public function update_bypasses_mass_assignment_when_pass_validated_input(): void
    {
        $post = Post::factory()->create();

        $title = fake()->sentence();
        $input = new ValidatedInput(['title' => $title]);

        $post->update($input);

        $this->assertDatabaseHas($post);
        $this->assertSame($title, $post->title);
    }
}
