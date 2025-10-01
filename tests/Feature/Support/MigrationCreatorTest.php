<?php

namespace Tests\Feature\Support;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \JMac\Additions\Support\MigrationCreator
 */
final class MigrationCreatorTest extends TestCase
{
    #[Test]
    #[DataProvider('defaultMigrations')]
    public function it_defers_to_core_for_create_migrations(string $name, ?string $table, bool $create): void
    {
        $subject = resolve('migration.creator');

        $path = $subject->create($name, database_path('migrations'), $table, $create);

        $this->assertFileEquals($this->migrationFixture($name), $path);
    }

    #[Test]
    #[DataProvider('unknownMigrations')]
    public function it_defers_to_core_for_unknown_migrations(string $name, ?string $table, bool $create): void
    {
        $this->markTestIncomplete();

        $subject = resolve('migration.creator');

        $path = $subject->create($name, database_path('migrations'), $table, $create);

        $this->assertFileEquals($this->migrationFixture($name), $path);
    }

    #[Test]
    #[DataProvider('guessableMigrations')]
    public function it_makes_additional_guessable_migrations(string $name, ?string $table, bool $create): void
    {
        $this->markTestIncomplete();

        $subject = resolve('migration.creator');

        $path = $subject->create($name, database_path('migrations'), $table, $create);

        $this->assertFileEquals($this->migrationFixture($name), $path);
    }

    private function migrationFixture(string $name): string
    {
        return $this->basePath('fixtures/migrations/'.$name.'.php');
    }

    public static function defaultMigrations(): array
    {
        return [
            ['create_comments_table', 'comments', true],
        ];
    }

    public static function guessableMigrations(): array
    {
        return [
            [],
        ];
    }

    public static function unknownMigrations(): array
    {
        return [
            [],
        ];
    }
}
