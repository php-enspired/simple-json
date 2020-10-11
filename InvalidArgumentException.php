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

/**
 * Represents invalid arguments passed to Json methods.
 */
class InvalidArgumentException extends InvalidArgumentExceptable {

  /** @var int DECODE_ASSOC must be boolean. */
  public const INVALID_DECODE_ASSOC = 1;

  /** @var int DECODE_DEPTH must be integer. */
  public const INVALID_DECODE_DEPTH = 2;

  /** @var int DECODE_FLAGS must be integer. */
  public const INVALID_DECODE_FLAGS = 3;

  /** @var int ENCODE_FLAGS must be integer. */
  public const INVALID_ENCODE_FLAGS = 4;

  /** @var int ENCODE_DEPTH must be integer. */
  public const INVALID_ENCODE_DEPTH = 5;

  /** @see IsExceptable::getInfo() */
  protected const INFO = [
    self::INVALID_DECODE_ASSOC => [
      "message" => "DECODE_ASSOC must be boolean",
      "format" => "DECODE_ASSOC must be boolean; {type} provided"
    ],
    self::INVALID_DECODE_DEPTH => [
      "message" => "DECODE_DEPTH must be integer",
      "format" => "DECODE_DEPTH must be integer; {type} provided"
    ],
    self::INVALID_DECODE_FLAGS => [
      "message" => "DECODE_FLAGS must be integer",
      "format" => "DECODE_FLAGS must be integer; {type} provided"
    ],
    self::INVALID_ENCODE_FLAGS => [
      "message" => "ENCODE_FLAGS must be integer",
      "format" => "ENCODE_FLAGS must be integer; {type} provided"
    ],
    self::INVALID_ENCODE_DEPTH => [
      "message" => "ENCODE_DEPTH must be integer",
      "format" => "ENCODE_DEPTH must be integer; {type} provided"
    ]
  ];
}
