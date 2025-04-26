=======
Install
=======

Installation of this module uses composer. For composer documentation, please
refer to `getcomposer.org <http://getcomposer.org/>`_ ::

.. code-block:: bash

  composer require laravel-doctrine/acl

To publish the config use:

.. code-block:: bash

  php artisan vendor:publish --tag="config" --provider="LaravelDoctrine\ACL\AclServiceProvider"

Thanks to Laravel auto package discovery, the ServiceProvider is
automatically registered.  However they can still be manually registered if
required (see below).

Manual Registration
===================

After updating composer, add the ServiceProvider to the providers
array in ``bootstrap/providers.php``

.. code-block:: php

  LaravelDoctrine\ACL\AclServiceProvider::class,

.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
