<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, DatabaseMigrations;

    /**
     *  Override set up method to seed default admin before test
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->artisan('migrate:fresh', ['--seed' => true]);

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');

            RefreshDatabaseState::$migrated = false;
        });
    }
}
