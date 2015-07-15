# Woohoo Labs. Yin

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/woohoolabs/yin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/yin/?branch=master)
[![Build Status](https://img.shields.io/travis/woohoolabs/yin.svg)](https://travis-ci.org/woohoolabs/yin)
[![Code Coverage](https://scrutinizer-ci.com/g/woohoolabs/yin/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/yin/?branch=master)
[![Stable Release](https://img.shields.io/packagist/v/woohoolabs/yin.svg)](https://packagist.org/packages/woohoolabs/yin)
[![License](https://img.shields.io/packagist/l/woohoolabs/yin.svg)](https://packagist.org/packages/woohoolabs/yin)

**Woohoo Labs. Yin is a library for HATEOAS API-s to transform resources to JSON API format easily and efficiently.**

## Introduction

[JSON API](http://jsonapi.org/) specification reached 1.0 on 29th May 2015 and we believe it is a big day for RESTful
API-s as this specification makes APIs more robust and future-proof than they have ever been. Woohoo Labs. Yin (named
after Yin-Yang) was born to bring efficiency and elegance for your JSON API definitions.

#### Features

As Woohoo Labs. Yin's development is in its very early stage, it lacks many features and should only be used to
experiment with it. But that's why all contributing is highly appreciated!

For now, Yin only supports some basic use-cases: single resource, collection and error documents can be sent as
responses, and retrieved fieldsets and included resources can be restricted. While documents can be compiled in quite a
spec-compliant way, we plan a lot more features like easier error-handling, support for create and update operations
and more restrictions (sorting, pagination and filtering).

## Install

The steps of this process are quite straightforward. The only thing you need is [Composer](http://getcomposer.org).

#### Add Yin to your composer.json:

To install this library, run the command below and you will get the latest version:

```bash
$ composer require woohoolabs/yin
```

#### Autoload in your bootstrap:

```php
require "vendor/autoload.php"
```

## Basic Usage

When using Woohoo Labs. Yin, you will create documents and resource transformers:

There are three main types of documents in the JSON API spec, and we provide an abstract class for each (at least for
now) which you have to extend: 

- ``AbstractSingleResourceDocument``: A class for single resource documents
- ``AbstractCollectionDocument``: A class for collection documents
- ``AbstractErrorDocument``: A class for error documents

And there is an ``AbstractResourceTransformer`` class for resource transformation.

Have a look at the [examples](https://github.com/woohoolabs/yin/tree/master/examples) if you want to get to know more
how Yin works: controllers contain the resources which will be transformed according to the JSON API spec. Set up a
web server and visit ``examples/index.php?example={{ EXAMPLE_NAME }}``, where ``EXAMPLE_NAME`` can be "book" or "users".
But don't forget first to run ``composer install`` in Yin's root directory. You can also restrict which fields and
attributes should be fetched. Let's see two example URIs:

- ``index.php?example=book&fields[book]=title,authors,publisher&fields[author]=name&fields[publisher]=name&include=authors,publisher``
- ``index.php?example=users&fields[user]=firstname,lastname,contacts&fields[contact]=phone_number,email&include=contacts``

Notice how transformation of resource attributes and relationships works (e.g.:
[`BookResourceTransformer`](https://github.com/woohoolabs/yin/blob/master/examples/JsonApi/Resource/BookResourceTransformer.php#L75)): 
you have to define anonymous functions for each attribute and relationship. This design allows us
to transform an attribute or a relationship only and if only it is requested. This is extremely advantageous when there
are a lot of resources to transform or a transformation is very expensive (I mean O(n<sup>2</sup>) or more).

## License

The MIT License (MIT). Please see the [License File](https://github.com/woohoolabs/yin/blob/master/LICENSE.md)
for more information.
