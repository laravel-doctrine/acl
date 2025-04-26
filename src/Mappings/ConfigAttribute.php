<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings;

use Illuminate\Contracts\Config\Repository;

interface ConfigAttribute
{
    public function getTargetEntity(Repository $config): string|null;
}
