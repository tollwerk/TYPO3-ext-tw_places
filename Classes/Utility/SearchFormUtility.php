<?php
/**
 * tw_places
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwPlaces
 * @subpackage Tollwerk\TwPlaces\Utility
 * @author     Klaus Fiedler <klaus@tollwerk.de>
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

namespace Tollwerk\TwPlaces\Utility;

use Tollwerk\TwGeo\Domain\Model\Position;
use Tollwerk\TwGeo\Utility\GeoUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class SearchFormUtility
 * @package Tollwerk\TwPlaces\Utility
 */
class SearchFormUtility implements SingletonInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager = null;

    /**
     * List of simple properties which can
     * be passed as constraints to PlaceRepository->search()
     * withouth further processing
     *
     * @var array
     */
    protected $simpleConstraints = [
        'country' => true,
    ];

    /**
     * SearchFormUtility constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param array $search
     * @return Position|null
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    public function getCoordinatesFromSearchForm(array $search): ?Position
    {
        // 1. Try to get latLon string directly from the geoselect-lat-lon field
        $latLon = !empty($search['geoselect-lat-lon']) ? $search['geoselect-lat-lon'] : null;
        if ($latLon) {
            // Return $latLon if it comes with the expected format of 'latitude:longitude*
            $latLon = explode(':', $latLon);
            if (count($latLon) === 2) {
                return new Position($latLon[0], $latLon[1]);
            }
        }

        /** @var GeoUtility $geoUtility */
        $geoUtility = $this->objectManager->get(GeoUtility::class);

        // 2. Try to geocode the search string
        if (!empty($search['geoselect-search'])) {
            $position = $geoUtility->geocode($search['geoselect-search']);
            if ($position) {
                return $position;
            }
        }

        // 3. Try to get the current location of the user
        return $geoUtility->getGeoLocation();
    }

    /**
     * Get valid constraints from search form
     *
     * @param array $search
     * @return array
     */
    public function getConstraintsFromSearchForm(array $search): array
    {
        $constraints = [];
        foreach($search as $property => $value) {
            if(array_key_exists($property, $this->simpleConstraints)) {
                $constraints[$property] = $value;
            }
        }
        return $constraints;
    }

}