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
    protected function tearDown(): void
    {
        exec('vendor/bin/testbench package:purge-skeleton');
        parent::tearDown();
    }

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
        $subject = resolve('migration.creator');

        $path = $subject->create($name, database_path('migrations'), $table, $create);

        $this->assertFileEquals($this->migrationFixture($name), $path);
    }

    #[Test]
    #[DataProvider('guessableMigrations')]
    public function it_makes_additional_guessable_migrations(string $name, ?string $table, bool $create, string $fixture): void
    {
        $subject = resolve('migration.creator');

        $path = $subject->create($name, database_path('migrations'), $table, $create);

        $this->assertFileEquals($this->migrationFixture($fixture), $path);
    }

    #[Test]
    public function it_handles_dashed_names(): void
    {
        $subject = resolve('migration.creator');

        $path = $subject->create('rename_stripe-id_to_transaction-id_in_account-orders_table', database_path('migrations'));

        $this->assertFileEquals($this->migrationFixture('dashed-names'), $path);
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
            ['drop_users_table', null, false, 'drop_users_table'],
            ['drop_users', null, false, 'drop_users_table'],
            ['remove_users_table', null, false, 'drop_users_table'],
            ['remove_users', null, false, 'drop_users_table'],
            ['rename_users_to_people_table', null, false, 'rename_users_table'],
            ['rename_users_to_people', null, false, 'rename_users_table'],
            ['drop_email_verified_at_from_users_table', null, false, 'drop_email_verified_at_from_users'],
            ['drop_email_verified_at_from_users', null, false, 'drop_email_verified_at_from_users'],
            ['rename_stripe_id_to_transaction_id_in_users_table', null, false, 'rename_stripe_id_to_transaction_id_in_users'],
            ['rename_stripe_id_to_transaction_id_in_users', null, false, 'rename_stripe_id_to_transaction_id_in_users'],
            ['alter_status_in_users_table', null, false, 'change_status_in_users'],
            ['alter_status_in_users', null, false, 'change_status_in_users'],
            ['change_status_in_users_table', null, false, 'change_status_in_users'],
            ['change_status_in_users', null, false, 'change_status_in_users'],
            ['add_stripe_id_to_users_table', null, false, 'add_stripe_id_to_users'],
            ['add_stripe_id_to_users', null, false, 'add_stripe_id_to_users'],
        ];
    }

    public static function unknownMigrations(): array
    {
        return [
            ['unknown_migration_action', null, false],
            ['alter_whatever_table', null, false],
        ];
    }
}
