<?php
namespace Tests;

use Workbench\App\Entities\User;

class LaravelSetupTest extends \Tests\TestCase
{
    public function testLaravelAppBoots()
    {
        $this->assertNotNull($this->app);
        $this->assertInstanceOf(\Illuminate\Foundation\Application::class, $this->app);
        $this->assertEquals('testing', $this->app->environment());

        $user = entity(User::class)->create();

        $this->actingAs($user);
    }
}
