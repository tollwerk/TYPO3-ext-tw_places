<?php
/**
 * tw_places
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwPlaces
 * @subpackage Tollwerk\TwPlaces\Domain\Repository
 * @author     Klaus Fiedler <klaus@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2020 Klaus Fiedler <klaus@tollwerk.de>
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

use Tollwerk\TwPlaces\Utility\SearchFormUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class PlaceController
 * @package Tollwerk\TwPlaces\Controller
 */
class PlaceController extends ActionController
{
    /**
     * @var SearchFormUtility
     */
    protected $searchFormUtility = null;

    /**
     * PlaceController constructor.
     * @param SearchFormUtility $searchFormUtility
     */
    public function __construct(SearchFormUtility $searchFormUtility)
    {
        $this->searchFormUtility = $searchFormUtility;
    }

    /**
     * List places
     * @param array $places Array with place uids
     */
    public function listAction(array $places = []): void
    {
        // TODO: Implement
    }

    /**
     * Processes the submitted search form values
     * and passes everything on to searchAction
     *
     * @param array $search
     */
    public function searchFormAction(array $search = [])
    {
        $position = $this->searchFormUtility->getCoordinatesFromSearchForm($search);
        $this->forward('search', 'Place', 'TwPlaces', [
            'latitude' => $position ? $position->getLatitude() : null,
            'longitude' => $position ? $position->getLongitude() : null,
            'constraints' => $this->searchFormUtility->getConstraintsFromSearchForm($search)
        ]);
    }

    /**
     * Search places nearby a desired location
     *
     * @param float $latitude The latitude
     * @param float $longitude The longitude
     * @param array $constraints Constraints for the search results like distance, categories etc.
     */
    public function searchAction(float $latitude = null, float $longitude = null, array $constraints = []): void
    {
        // TODO: Implement
    }

    /**
     * Show a single place
     * @param int $place A single place uid
     */
    public function showAction(int $place)
    {
        // TODO: Implement
    }
}