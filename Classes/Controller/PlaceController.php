<?php
/**
 * tw_places
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwPlaces
 * @subpackage Tollwerk\TwPlaces\Domain\Repository
 * @author     Klaus Fiedler <klaus@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2019 Klaus Fiedler <klaus@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2020 Klaus Fiedler <klaus@tollwerk.de>
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

namespace Tollwerk\TwPlaces\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class PlaceController
 * @package Tollwerk\TwPlaces\Controller
 */
class PlaceController extends ActionController
{


    /**
     * List places
     * @param array $places    Array with place uids
     */
    public function listAction(array $places = null): void
    {

    }

    /**
     * Search places nearby a desired location
     *
     * @param string $latLon        Latitude and longitude, separated by a colon. Example: '50.284923:11.909763'
     * @param string $search        If no $latLon given, will be geocoded and taken as position to search from instead
     * @param array $constraints    Constraints for the search results like distance, categories etc.
     */
    public function searchAction(string $latLon = null, string $search = null, array $constraints = null): void
    {

    }

    /**
     * Show a single place
     * @param int $place    A single place uid
     */
    public function showAction(int $place)
    {

    }
}