<?php
namespace Tests;

use Doctrine\ORM\EntityManager;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Mockery as m;

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
