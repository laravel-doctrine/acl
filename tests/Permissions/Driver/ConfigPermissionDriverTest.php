<?php

declare(strict_types=1);

namespace Tests\Permissions;

use LaravelDoctrine\ACL\Permissions\Driver\Config;
use PHPUnit\Framework\TestCase;

class ConfigPermissionDriverTest extends TestCase
{
    public function testCanGetAllPermissions(): void
    {
        $emptyConfig = new Config([]);
        $this->assertTrue($emptyConfig->getAllPermissions()->isEmpty());

        $config = new Config(['mocked']);
        $this->assertTrue($config->getAllPermissions()->contains('mocked'));
    }
}
