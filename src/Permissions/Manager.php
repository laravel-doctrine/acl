<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Configuration\Manager as ConfigurationManager;
use LaravelDoctrine\ORM\Exceptions\DriverNotFound;

use function class_exists;
use function sprintf;

abstract class Manager extends ConfigurationManager
{
    /**
     * Create a new driver instance.
     *
     * @param array<string, mixed> $settings
     *
     * @throws DriverNotFound
     */
    protected function createDriver(string $driver, array $settings = [], bool $resolve = true): mixed
    {
        $class = $this->getNamespace() . '\\' . Str::studly($driver) . $this->getClassSuffix();

        // We'll check to see if a creator method exists for the given driver. If not we
        // will check for a custom driver creator, which allows developers to create
        // drivers using their own customized driver creator Closure to create it.
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        }

        if (class_exists($class)) {
            return $this->container->make($class);
        }

        throw new DriverNotFound(sprintf('Driver [%s] not supported.', $driver));
    }
}
