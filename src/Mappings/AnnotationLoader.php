<?php

namespace LaravelDoctrine\ACL\Mappings;

class AnnotationLoader
{
    /**
     * @const
     */
    const ANNOTATION_NAMESPACE = 'LaravelDoctrine\\ACL\\Mappings\\';

    /**
     * @param $class
     *
     * @return mixed
     */
    public function loadClass($class)
    {
        if (strpos($class, self::ANNOTATION_NAMESPACE) === 0) {
            $class = str_replace(self::ANNOTATION_NAMESPACE, '', $class);

            $file = __DIR__ . '/' . $class . '.php';
            if (file_exists($file)) {
                require_once $file;

                return true;
            }
        }
    }
}
