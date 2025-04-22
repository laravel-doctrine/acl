<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Workbench\App\Entities\User;

use function entity;

class LaravelSetupTest extends TestCase
{
    public function testLaravelAppBoots(): void
    {
        $this->assertNotNull($this->app);
        $this->assertInstanceOf(Application::class, $this->app);
        $this->assertEquals('testing', $this->app->environment());

        $user = entity(User::class)->create();

        $this->actingAs($user);
    }
}
