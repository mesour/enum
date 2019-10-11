mesour/enum
===========

The main advantages of using enums for representing set of values are:

* you can be sure, that the value is unchanged (not only validated once)
* you can use type hints to check that only the defined set of values is acceptable
* you can define behavior on top of the represented values
* you can define properties for enum values

Requirements
------------

Library mesour/enum requires PHP 7.1 or higher.

Installation
------------

The best way to install mesour/enum is using [Composer](http://getcomposer.org/).

-  Run command `composer require mesour/enum`.

Documentation
-------------

Learn more in the [documentation](https://github.com/mesour/enum/blob/master/docs/en/index.md).

How to run tests
----------------

- Run command `vendor/bin/tester -c tests/php.ini tests`.
