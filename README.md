php-etl
=======

[![Build Status](https://travis-ci.org/docteurklein/php-etl.png?branch=master)](https://travis-ci.org/docteurklein/php-etl)

php-etl is a PHP 5.4+ library that follows the well-known `Extract | Transform | Load` pattern.

It provides a few extractors, a few transformers and a few loaders to import csv data into a RDBM for example.

## Usage

``` shell

# get composer

php composer.phar install --prefer-dist
bin/pimple-etl config.sample.php # example usage

```

## Contribute

``` shell

# get composer

php composer.phar install --prefer-dist --dev
bin/phpspec desc Knp\ETL\Feature\Class
vim src/Knp/ETL/Feature/Class.php

bin/phpspec run -f pretty

```
