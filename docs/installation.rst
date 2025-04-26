============
Installation
============

Install via Composer:

.. code-block:: bash

   composer require laravel-doctrine/acl

Package should be automatically registered but in case no add it to `bootstrap/providers.php`:

.. code-block:: php

   LaravelDoctrine\ACL\AclServiceProvider::class,

Publish the configuration:

.. code-block:: bash

   php artisan vendor:publish --tag="config" --provider="LaravelDoctrine\ACL\AclServiceProvider"


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
