<?php return array (
  'laravel-doctrine/migrations' => 
  array (
    'providers' => 
    array (
      0 => 'LaravelDoctrine\\Migrations\\MigrationsServiceProvider',
    ),
  ),
  'laravel-doctrine/orm' => 
  array (
    'aliases' => 
    array (
      'Doctrine' => 'LaravelDoctrine\\ORM\\Facades\\Doctrine',
      'Registry' => 'LaravelDoctrine\\ORM\\Facades\\Registry',
      'EntityManager' => 'LaravelDoctrine\\ORM\\Facades\\EntityManager',
    ),
    'providers' => 
    array (
      0 => 'LaravelDoctrine\\ORM\\DoctrineServiceProvider',
    ),
  ),
  'laravel/pail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Pail\\PailServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'nunomaduro/termwind' => 
  array (
    'providers' => 
    array (
      0 => 'Termwind\\Laravel\\TermwindServiceProvider',
    ),
  ),
  'orchestra/canvas' => 
  array (
    'providers' => 
    array (
      0 => 'Orchestra\\Canvas\\LaravelServiceProvider',
    ),
  ),
  'orchestra/canvas-core' => 
  array (
    'providers' => 
    array (
      0 => 'Orchestra\\Canvas\\Core\\LaravelServiceProvider',
    ),
  ),
  'sowl/laravel-doctrine-acl' => 
  array (
    'providers' => 
    array (
      0 => 'LaravelDoctrine\\ACL\\AclServiceProvider',
    ),
  ),
);