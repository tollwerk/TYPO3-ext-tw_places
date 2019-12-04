<?php
/**
 * tw_places
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwPlaces
 * @subpackage Tollwerk\TwPlaces\Evaluation
 * @author     Klaus Fiedler <klaus@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2019 Klaus Fiedler <klaus@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2019 Klaus Fiedler <klaus@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Tollwerk\TwPlaces\Evaluation;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CoordinateEvaluation
 *
 * Evaluate latitude and longitude fields in TCA
 *
 * @package    Tollwerk\TwPlaces
 * @subpackage Tollwerk\TwPlaces\Evaluation
 */
class CoordinateEvaluation
{
    /**
     * Evaluates a given string for compliance with latitude / longitude format
     *
     * @param $value
     *
     * @return string
     */
    public static function coordinateEval(string $value): string
    {
        $value = str_replace(',', '.', $value);
        $parts = GeneralUtility::trimExplode('.', $value);

        $before = array_shift($parts);
        if (count($parts)) {
            $after = substr(implode('', $parts), 0, 15);
        }

        return $before.'.'.$after;
    }

    /**
     * JavaScript code for client side validation/evaluation
     *
     * @return string JavaScript code for client side validation/evaluation
     */
    public function returnFieldJS(): string
    {
        return 'return value'; // + " [added by JavaScript on field blur]";';
    }

    /**
     * Server-side validation/evaluation on saving the record
     *
     * @param string $value The field value to be evaluated
     * @param string $is_in The "is_in" value of the field configuration from TCA
     * @param bool $set     Boolean defining if the value is written to the database or not.
     *
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value, $is_in, &$set) : string
    {
        die("2");
        return self::coordinateEval($value);
    }

    /**
     * Server-side validation/evaluation on opening the record
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     *
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters) : string
    {
        die("3");
        return self::coordinateEval($parameters['value']);
    }
}