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

use AT\Exceptable\Spl\InvalidArgumentException as InvalidArgumentExceptable;

use const JSON_ERROR_UNSUPPORTED_TYPE;

/**
 * Represents invalid arguments passed to Json methods.
 */
class InvalidArgumentException extends InvalidArgumentExceptable {

  /**
   * @var int INVALID_ASSOC         Option must be boolean.
   * @var int INVALID_DEPTH         Option must be integer.
   * @var int INVALID_DECODE_FLAGS  Option must be integer.
   * @var int INVALID_ENCODE_FLAGS  Option must be integer.
   * @var int UNSUPPORTED_TYPE      @see https://php.net/json.constants JSON_ERROR_UNSUPPORTED_TYPE
   */
  public const INVALID_ASSOC = 1;
  public const INVALID_DEPTH = 2;
  public const INVALID_DECODE_FLAGS = 3;
  public const INVALID_ENCODE_FLAGS = 4;
  public const UNSUPPORTED_TYPE = JSON_ERROR_UNSUPPORTED_TYPE;

  /** @see IsExceptable::getInfo() */
  protected const INFO = [
    self::INVALID_ASSOC => [
      "message" => "ASSOC must be boolean",
      "format" => "ASSOC must be boolean; {type} provided"
    ],
    self::INVALID_DEPTH => [
      "message" => "DEPTH must be integer",
      "format" => "DEPTH must be integer; {type} provided"
    ],
    self::INVALID_DECODE_FLAGS => [
      "message" => "DECODE_FLAGS must be integer",
      "format" => "DECODE_FLAGS must be integer; {type} provided"
    ],
    self::INVALID_ENCODE_FLAGS => [
      "message" => "ENCODE_FLAGS must be integer",
      "format" => "ENCODE_FLAGS must be integer; {type} provided"
    ],
    self::UNSUPPORTED_TYPE => [
      "message" => "Type is not supported",
      "format" => "Type is not supported: {type}"
    ]
  ];
}
