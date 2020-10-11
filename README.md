![](https://img.shields.io/github/release/php-enspired/simple-json.svg)  ![](https://img.shields.io/badge/PHP-7.3-blue.svg?colorB=8892BF)  ![](https://img.shields.io/badge/license-GPL_3.0_only-blue.svg)

it's simple: json
=================

_Simple_ packages are focused on being straightforward and unopinionated solutions for common needs.

_Json_ is a convenience wrapper for json encoding/decoding. Its main purpose is to make managing encodin/decode options easy.

dependencies
------------

Requires php 7.3 or later.

installation
------------

Recommended installation method is via [Composer](https://getcomposer.org/): simply `composer require php-enspired/simple-json`.

basic usage
-----------
```php
<?php

use AT\Simple\Json\Json;

$a = ["foo" => "one", "bar" => "two"];

// basic encoding and decoding (note $assoc = true is the default mode)
$json = Json::encode($a);  // {"foo":"one","bar":"two"}
var_dump($a === Json::decode());  // bool (true)

// passing special options
Json::encode($a, [Json::ENCODE_FLAGS => Json::ENCODE_PRETTY]);
/*
{
    "foo": "one",
    "bar": "two"
}
*/

var_dump(Json::decode($json, [Json::DECODE_ASOOC => false]));
/*
object(stdClass)#1 (2) {
  ["foo"]=>
  string(3) "one"
  ["bar"]=>
  string(3) "two"
}
*/

Json::isValid('["oh, foo"', $e);
echo $e->getMessage();  // Syntax error
```

By default, objects are decoded as associative arrays and big integers are decoded as strings (rather than converting them to float).
When encoding data, big integers are encoded as strings, "zero" fractions are preserved (rather then encoding them as integers), and slashes and unicode characters are not escaped.

Both encoding and decoding throw on error; this cannot be overridden.

Json defines some convenience constants for sets of encode/decode options:
- `ENCODE_ASCII`: default encoding options, but unsets `JSON_UNESCAPED_UNICODE`.
- `ENCODE_HEX`: all of the `JSON_HEX_*` options.
- `ENCODE_HTML`: default encoding options, but unsets `JSON_UNESCAPED_SLASHES`.
- `ENCODE_PRETTY`: default encoding options, and also sets `JSON_PRETTY_PRINT`.

docs
----

_coming soon_

contributing or getting help
----------------------------

I'm on [Freenode at `#php-enspired`](http://webchat.freenode.net?channels=%23php-enspired&uio=d4), or open an issue [on github](https://github.com/php-enspired/simple-json/issues).  Feedback is welcomed.
