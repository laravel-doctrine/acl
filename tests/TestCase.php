<?php
namespace Tests;

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
}
