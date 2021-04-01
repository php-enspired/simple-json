![](https://img.shields.io/github/release/php-enspired/simple-json.svg)  ![](https://img.shields.io/badge/PHP-7.3-blue.svg?colorB=8892BF)  ![](https://img.shields.io/badge/license-GPL_3.0_only-blue.svg)

it's simple: json
=================

_Simple_ packages are focused on being straightforward, clean, concise solutions for common needs.

_Json_ is a convenience wrapper for json encoding/decoding. Its main purpose is to set sane defaults and make managing encoding/decoding options easy.

dependencies
------------

Requires php 7.3 or later.

installation
------------

Recommended installation method is via [Composer](https://getcomposer.org/): simply `composer require php-enspired/simple-json`.

basic usage
-----------
By default, objects are decoded as associative arrays and big integers are decoded as strings (rather than converting them to float).

When encoding data, big integers are encoded as strings, "zero" fractions are preserved (rather then encoding them as integers), and slashes and unicode characters are not escaped. To prevent unexpected/unpredictable results, objects will not be encoded unless they are `stdClass` or implement `JsonSerializable`.

Both encoding and decoding throw on error; this cannot be overridden.

Json defines some constants for sets of encode/decode options. As a convenience, these options are also settable via static factory methods.
- `ENCODE_ASCII`: default encoding options, but unsets `JSON_UNESCAPED_UNICODE`.
- `ENCODE_HEX`: all of the `JSON_HEX_*` options.
- `ENCODE_HTML`: default encoding options, but unsets `JSON_UNESCAPED_SLASHES`.
- `ENCODE_PRETTY`: default encoding options, and also sets `JSON_PRETTY_PRINT`.

```php
<?php

use AT\Simple\Json\Json;

// example data
$a = ["foo" => "one", "bar" => "two"];

// basic encoding and decoding (note $assoc = true is the default mode)
$json = Json::default();
$j = $json->encode($a);  // {"foo":"one","bar":"two"}
$a === $json->decode($j);  // bool (true)

// special options - e.g., "pretty" formatting
Json::pretty()->encode($a);
/*
{
    "foo": "one",
    "bar": "two"
}
*/

// decoding objects as stdClass
(new Json([Json::DECODE_ASOOC => false]))->decode($j);
/*
object(stdClass)#1 (2) {
  ["foo"]=>
  string(3) "one"
  ["bar"]=>
  string(3) "two"
}
*/
```

docs
----

- **[Getting Started (It's Simple)](/php-enspired/simple-json/wiki/It's-Simple:-Json)**
  - [dependencies](https://github.com/php-enspired/simple-json/wiki/It's-Simple:-Json#dependencies)
  - [installation](https://github.com/php-enspired/simple-json/wiki/It's-Simple:-Json#installation)
  - [basic usage](https://github.com/php-enspired/simple-json/wiki/It's-Simple:-Json#basic-usage)
- **[Constructor and Factory Methods](/php-enspired/simple-json/wiki/Constructor-and-Factory-Methods)**
  - [`__construct()`](/php-enspired/simple-json/wiki/Constructor-and-Factory-Methods#__construct-array-options---)
  - [`::default()`](/php-enspired/simple-json/wiki/Constructor-and-Factory-Methods#static-default-void---json)
  - [`::ascii()`](/php-enspired/simple-json/wiki/Constructor-and-Factory-Methods#static-ascii-void---json)
  - [`::hex()`](/php-enspired/simple-json/wiki/Constructor-and-Factory-Methods#static-hex-void---json)
  - [`::html()`](/php-enspired/simple-json/wiki/Constructor-and-Factory-Methods#static-html-void---json)
  - [`::pretty()`](/php-enspired/simple-json/wiki/Constructor-and-Factory-Methods#static-pretty-void---json)
- **[Encoding and Decoding](/php-enspired/simple-json/wiki/Encoding-and-Decoding)**
  - [`encode()`](/php-enspired/simple-json/wiki/Encoding-and-Decoding#encode-mixed-data--bool-strict--true---string)
  - [`decode()`](/php-enspired/simple-json/wiki/Encoding-and-Decoding#decode-string-json---mixed)
- **[Managing Options](/php-enspired/simple-json/wiki/Managing-Options)**
  - [`setOptions()`](/php-enspired/simple-json/wiki/Managing-Options#setoptions-array-options---json)
- **[Handling Errors](/php-enspired/simple-json/wiki/Handling-Errors)**
  - [`JsonException`](/php-enspired/simple-json/wiki/Handling-Errors#jsonexception)
  - [`InvalidArgumentException`](/php-enspired/simple-json/wiki/Handling-Errors#invalidargumentexception)

contributing or getting help
----------------------------

I'm on [Freenode at `#php-enspired`](http://webchat.freenode.net?channels=%23php-enspired&uio=d4), or open an issue [on github](https://github.com/php-enspired/simple-json/issues).  Feedback is welcomed.
