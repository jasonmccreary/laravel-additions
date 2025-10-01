<?php

namespace Tests\Feature\Support;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Workbench\App\Models\Post;

final class MigrationCreatorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[DataProvider('defaultMigrations')]
    public function it_defers_to_core_for_create_migrations(string $name, ?string $table, bool $create): void
    {
        $subject = resolve('migration.creator');

        dump('here');

        $subject->create($name, database_path('migrations'), $table, $create);
    }

    #[Test]
    #[DataProvider('unknownMigrations')]
    public function it_defers_to_core_for_unknown_migrations(): void
    {
        $this->assertNull(Post::findByTitle('does not exist'));
    }

    #[Test]
    #[DataProvider('guessableMigrations')]
    public function it_makes_additional_guessable_migrations(): void
    {
        $this->assertNull(Post::findByTitle('does not exist'));
    }

    private function migrationFixture(string $name): string
    {
        return $this->basePath().'fixtures/migrations/'.$name.'.php';
    }

    public static function defaultMigrations(): array
    {
        return [
            ['create_posts_table', 'posts', true],
            //            ['create_password_resets', 'password_resets', true],
        ];
    }

    public static function guessableMigrations(): array
    {
        return [];
    }

    public static function unknownMigrations(): array
    {
        return [];
    }
}
