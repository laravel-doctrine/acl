<?php

declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManager;
use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('doctrine:schema:create');
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    protected function em(): EntityManager
    {
        return $this->app->make(EntityManager::class);
    }
}
