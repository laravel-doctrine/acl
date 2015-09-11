<?php

namespace LaravelDoctrine\ACL\Mappings;

use Illuminate\Contracts\Config\Repository;

interface ConfigAnnotation
{
    /**
     * @param Repository $config
     *
     * @return mixed
     */
    public function getTargetEntity(Repository $config);
}
