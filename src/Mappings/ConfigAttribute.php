<?php

namespace LaravelDoctrine\ACL\Mappings;

use Illuminate\Contracts\Config\Repository;

interface ConfigAttribute
{
    /**
     * @param \Illuminate\Contracts\Config\Repository $config
     * @return mixed
     */
    public function getTargetEntity(\Illuminate\Contracts\Config\Repository $config);
}