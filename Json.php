<?php
/**
 * @package    at.simple-json
 * @author     Adrian <adrian@enspi.red>
 * @copyright  2020
 * @license    GPL-3.0 (only)
 *
 *  This program is free software: you can redistribute it and/or modify it
 *  under the terms of the GNU General Public License, version 3.
 *  The right to apply the terms of later versions of the GPL is RESERVED.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 *  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *  See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with this program.
 *  If not, see <http://www.gnu.org/licenses/gpl-3.0.txt>.
 */
declare(strict_types = 1);

namespace AT\Simple\Json;

use JsonException,
  JsonSerializable,
  stdClass;

use AT\Simple\Json\InvalidArgumentException;

use const JSON_ERROR_UNSUPPORTED_TYPE;

/**
 * Convenience wrapper for json encoding/decoding.
 */
class Json {

  /**
   * Keys for $options tuples.
   *
   * @type int ASSOC         Decode objects as arrays?
   * @type int DEPTH         Maximum recursion level
   * @type int DECODE_FLAGS  Decoding options
   * @type int ENCODE_FLAGS  Encoding options
   */
  public const ASSOC = 0;
  public const DEPTH = 1;
  public const DECODE_FLAGS = 2;
  public const ENCODE_FLAGS = 3;

  /**
   * Default encode and decode options.
   *
   * @type bool DEFAULT_ASSOC         Prefer decoding data as arrays
   * @type int  DEFAULT_DEPTH         Default depth
   * @type int  DEFAULT_DECODE_FLAGS  Preferred options for json_decode
   * @type int  DEFAULT_ENCODE_FLAGS  Preferred options for json_encode
   */
  protected const DEFAULT_ASSOC = true;
  protected const DEFAULT_DEPTH = 512;
  protected const DEFAULT_DECODE_FLAGS = JSON_BIGINT_AS_STRING;
  protected const DEFAULT_ENCODE_FLAGS = JSON_BIGINT_AS_STRING |
    JSON_PRESERVE_ZERO_FRACTION |
    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE;

  /**
   * Encode options.
   *
   * @type int ENCODE_ASCII   Default encoding options + \u encoding non-ascii characters
   * @type int ENCODE_HEX     All JSON_HEX_* options
   * @type int ENCODE_HTML    Default encoding options + escaped slashes
   * @type int ENCODE_PRETTY  Default encoding options + pretty printing
   */
  public const ENCODE_ASCII = self::DEFAULT_ENCODE_FLAGS & ~ JSON_UNESCAPED_UNICODE;
  public const ENCODE_HEX = JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS;
  public const ENCODE_HTML = self::DEFAULT_ENCODE_FLAGS & ~ JSON_UNESCAPED_SLASHES;
  public const ENCODE_PRETTY = self::DEFAULT_ENCODE_FLAGS | JSON_PRETTY_PRINT;

  /**
   * Factory: convenience method for building a new Json instance with "ascii" options.
   *
   * @return Json
   */
  public static function ascii() : Json {
    return new self([self::ENCODE_FLAGS => self::ENCODE_ASCII]);
  }

  /**
   * Factory: convenience method for building a new Json instance with default options.
   *
   * @return Json
   */
  public static function default() : Json {
    return new self();
  }

  /**
   * Factory: convenience method for building a new Json instance with "hex" options.
   *
   * @return Json
   */
  public static function hex() : Json {
    return new self([self::ENCODE_FLAGS => self::ENCODE_HEX]);
  }

  /**
   * Factory: convenience method for building a new Json instance with "html" options.
   *
   * @return Json
   */
  public static function html() : Json {
    return new self([self::ENCODE_FLAGS => self::ENCODE_HTML]);
  }

  /**
   * Factory: convenience method for building a new Json instance with "pretty" options.
   *
   * @return Json
   */
  public static function pretty() : Json {
    return new self([self::ENCODE_FLAGS => self::ENCODE_PRETTY]);
  }

  /**
   * Parsed encode/decode options.
   */
  protected $assoc;
  protected $depth;
  protected $decodeFlags;
  protected $encodeFlags;

  /**
   * @param array $options  @see setOptions()
   */
  public function __construct(array $options = []) {
    $this->setOptions($options);
  }

  /**
   * Decodes a Json string.
   *
   * @param string $json    The json string to decode
   * @throws JsonException  If decoding fails
   * @return mixed          The decoded data on success
   */
  public static function decode(string $json) {
    return json_decode($json, $this->assoc, $this->depth, $this->decodeFlags);
  }

  /**
   * Encodes a value as Json.
   *
   * Note, objects are considered "encodable" only if they are stdClass or JsonSerializable.
   * Pass $strict = false to override this.
   *
   * @param mixed $data     Data to encode
   * @param bool  $strict   Don't encode non-json-able objects?
   * @throws JsonException  If encoding fails
   * @return string         The encoded json string on success
   */
  public static function encode($data, bool $strict = true) : string {
    if (
      is_object($data) &&
      ! ($data instanceof stdClass || $data instanceof JsonSerializable)
    ) {
      $e = new InvalidArgumentException(
        InvalidArgumentException::UNSUPPORTED_TYPE,
        ['type' => get_class($data)]
      );
      throw new JsonException($e->getMessage(), JSON_ERROR_UNSUPPORTED_TYPE, $e);
    }

    return json_encode($data, $this->encodeFlags, $this->depth);
  }

  /**
   * Sets encode/decode options.
   *
   * @param array $options             Options to parse: {
   *  @var bool ${self::ASSOC}         @see https://php.net/json_decode $assoc
   *  @var int  ${self::DEPTH}         @see https://php.net/json_decode $depth
   *  @var int  ${self::DECODE_FLAGS}  @see https://php.net/json_decode $options
   *  @var int  ${self::ENCODE_FLAGS}  @see https://php.net/json_encode $options
   * }
   * @throws InvalidArgumentException  If any options are invalid
   * @return Json                      $this
   */
  public function setOptions(array $options) : Json {
    $assoc = $options[self::DECODE_ASSOC] ?? self::DEFAULT_ASSOC;
    if (! is_bool($assoc)) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_ASSOC,
        ["type" => gettype($assoc)]
      );
    }

    $depth = $options[self::DECODE_DEPTH] ?? self::DEFAULT_DEPTH;
    if (! is_int($depth) || $depth < 0) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_DEPTH,
        ["type" => gettype($depth)]
      );
    }

    $decodeFlags = $options[self::DECODE_FLAGS] ?? self::DEFAULT_DECODE_FLAGS;
    if (! is_int($flags) || $depth < 0) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_DECODE_FLAGS,
        ["type" => gettype($flags)]
      );
    }

    $encodeFlags = $options[self::ENCODE_FLAGS] ?? self::DEFAULT_ENCODE_FLAGS;
    if (! is_int($flags) || $depth < 0) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_ENCODE_FLAGS,
        ["type" => gettype($flags)]
      );
    }

    $this->assoc = $assoc;
    $this->depth = $depth;
    $this->decodeFlags = $decodeFlags | JSON_THROW_ON_ERROR;
    $this->encodeFlags = $encodeFlags | JSON_THROW_ON_ERROR;

    return $this;
  }
}
