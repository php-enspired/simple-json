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

/**
 * Convenience wrapper for json encoding/decoding.
 */
class Json {

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
   * Keys for decode/encode $options tuples.
   *
   * @type int DECODE_ASSOC  Decode objects as arrays?
   * @type int DECODE_DEPTH  Maximum recursion level to decode
   * @type int DECODE_FLAGS  Decoding options
   * @type int ENCODE_FLAGS  Encoding options
   * @type int ENCODE_DEPTH  Maximum recursion level to encode
   */
  public const DECODE_ASSOC = 0;
  public const DECODE_DEPTH = 1;
  public const DECODE_FLAGS = 2;
  public const ENCODE_FLAGS = 0;
  public const ENCODE_DEPTH = 1;

  /** @var int  Error code: Json is a string encoding format. */
  public const JSON_MUST_BE_STRING = 66;

  /**
   * Default encode and decode options.
   *
   * @type bool DEFAULT_ASSOC         Prefer decoding data as arrays
   * @type int  DEFAULT_DECODE_FLAGS  Preferred options for json_decode
   * @type int  DEFAULT_ENCODE_FLAGS  Preferred options for json_encode
   * @type int  DEFAULT_DEPTH         Default depth
   */
  protected const DEFAULT_ASSOC = true;
  protected const DEFAULT_DECODE_FLAGS = JSON_BIGINT_AS_STRING;
  protected const DEFAULT_ENCODE_FLAGS = JSON_BIGINT_AS_STRING |
    JSON_PRESERVE_ZERO_FRACTION |
    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE;
  protected const DEFAULT_DEPTH = 512;

  /**
   * Decodes a Json string.
   *
   * @param string $json    The json string to decode
   * @param array $options  Options for decoding: {
   *  @var bool ${self::DECODE_ASSOC}  @see https://.php.net/json_decode $assoc
   *  @var int  ${self::DECODE_DEPTH}  @see https://.php.net/json_decode $depth
   *  @var int  ${self::DECODE_FLAGS}  @see https://.php.net/json_decode $options
   * }
   * @throws InvalidArgumentException  INVALID_DECODE_ASSOC if DECODE_ASSOC is not bool
   * @throws InvalidArgumentException  INVALID_DECODE_DEPTH if DECODE_DEPTH is not an int
   * @throws InvalidArgumentException  INVALID_DECODE_FLAGS if DECODE_FLAGS is not an int
   * @throws JsonException             If decoding fails
   * @return mixed                     The decoded data on success
   */
  public static function decode(string $json, array $options = []) {
    return json_decode($json, ...self::parseDecodeOptions($options));
  }

  /**
   * Encodes a value as Json.
   *
   * @param mixed $data     Data to encode
   * @param array $options  Options for encoding: {
   *  @var int ${self::ENCODE_FLAGS}  @see https://.php.net/json_encode $options
   *  @var int ${self::ENCODE_DEPTH}  @see https://.php.net/json_encode $depth
   * }
   * @throws InvalidArgumentException  INVALID_ENCODE_FLAGS if ENCODE_FLAGS is not an int
   * @throws InvalidArgumentException  INVALID_ENCODE_DEPTH if ENCODE_DEPTH is not an int
   * @throws JsonException             If encoding fails
   * @return string                    The encoded json string on success
   */
  public static function encode($data, array $options = []) : string {
    return json_encode($data, ...self::parseEncodeOptions($options));
  }

  /**
   * Can the given value be encoded as json?
   *
   * Note; this method considers objects "encodable" only if they are stdClass or JsonSerializable.
   */
  public static function isJsonable($value) : bool {
    return is_object($value) ?
      ($value instanceof stdClass || $value instanceof JsonSerializable) :
      ! is_resource($value);
  }

  /**
   * Is the given value a valid json string?
   *
   * @param mixed          $value   The value to check
   * @param Throwable|null &$error  Filled if json is invalid; null otherwise
   * @return bool                   True if value is valid json; false otherwise
   */
  public static function isValid($value, &$error = null) : bool {
    $error = null;

    try {
      self::decode($value);
      return true;
    } catch (TypeError | JsonException $e) {
      $error = $e;
      return false;
    }
  }

  /**
   * Parses decode options.
   *
   * @param array $options             Options to parse
   * @throws InvalidArgumentException  If any options are invalid; @see Json::decode() $options
   * @return array                     Options tuple: [assoc, depth, flags]
   */
  protected static function parseDecodeOptions(array $options) : array {
    $assoc = $options[self::DECODE_ASSOC] ?? self::DEFAULT_ASSOC;
    if (! is_bool($assoc)) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_DECODE_ASSOC,
        ["type" => gettype($assoc)]
      );
    }

    $depth = $options[self::DECODE_DEPTH] ?? self::DEFAULT_DEPTH;
    if (! is_int($depth)) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_DECODE_DEPTH,
        ["type" => gettype($depth)]
      );
    }

    $flags = $options[self::DECODE_FLAGS] ?? self::DEFAULT_DECODE_FLAGS;
    if (! is_int($flags)) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_DECODE_FLAGS,
        ["type" => gettype($flags)]
      );
    }

    return [$assoc, $depth, $flags | JSON_THROW_ON_ERROR];
  }

  /**
   * Parses encode options.
   *
   * @param array $options             Options to parse
   * @throws InvalidArgumentException  If any options are invalid; @see Json::encode() $options
   * @return array                     Options tuple: [flags, depth]
   */
  protected static function parseEncodeOptions(array $options) : array {
    $flags = $options[self::ENCODE_FLAGS] ?? self::DEFAULT_ENCODE_FLAGS;
    if (! is_int($flags)) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_ENCODE_FLAGS,
        ["type" => gettype($flags)]
      );
    }

    $depth = $options[self::ENCODE_DEPTH] ?? self::DEFAULT_DEPTH;
    if (! is_int($depth)) {
      InvalidArgumentException::throw(
        InvalidArgumentException::INVALID_ENCODE_DEPTH,
        ["type" => gettype($depth)]
      );
    }

    return [$flags | JSON_THROW_ON_ERROR, $depth];
  }
}
