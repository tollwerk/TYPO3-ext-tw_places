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

use Tollwerk\TwGeo\Utility\GeoUtility;
use Tollwerk\TwPlaces\Domain\Repository\PlaceRepository;
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

    /** @var GeoUtility */
    protected $geoUtility = null;

    /**
     * @var PlaceRepository
     */
    protected $placeRepository = null;

    /**
     * PlaceController constructor.
     *
     * @param SearchFormUtility $searchFormUtility
     * @param PlaceRepository $placeRepository
     */
    public function __construct(SearchFormUtility $searchFormUtility, PlaceRepository $placeRepository, GeoUtility $geoUtility)
    {
        $this->searchFormUtility = $searchFormUtility;
        $this->placeRepository = $placeRepository;
        $this->geoUtility = $geoUtility;
    }

    /**
     * Processes the submitted list form values
     * and passes everything on to listAction
     *
     * @param array $placeSearchForm
     */
    public function listFormAction(array $placeListForm = [])
    {
        $filters = [];

        if (!empty($placeListForm['country'])) {
            $filters['country'] = $placeListForm['country'];
        }

        $this->forward('list', 'Place', 'TwPlaces', [
            'filters' => $filters,
        ]);
    }

    /**
     * List places
     *
     * @param array $filters Array with filters
     */
    public function listAction(array $filters = []): void
    {
        $position = $this->geoUtility->getGeoLocation();

        $places = !empty($filters) ? $this->placeRepository->search(
            $position ? $position->getLatitude() : null,
            $position ? $position->getLongitude() : null,
            $filters
        ) : [];
        $this->view->assign('places', $places);
    }

    /**
     * Search places nearby a desired location
     *
     * @param array $placeSearchForm
     */
    public function searchAction(array $placeSearchForm = []): void
    {
        // Get values from $placeSearchForm
        $position = $this->searchFormUtility->getCoordinatesFromSearchForm($placeSearchForm);
        $latitude = $position ? $position->getLatitude() : null;
        $longitude = $position ? $position->getLongitude() : null;
        $constraints = $this->searchFormUtility->getConstraintsFromSearchForm($placeSearchForm);
        $searchTerm = !empty($placeSearchForm['geoselect-search']) ? $placeSearchForm['geoselect-search'] : null;
        $this->view->assign('searchTerm', $searchTerm);

        // Set default constraints. TODO: Get from configuration / placeSearchForm
        $constraints['limit'] = 3;

        // Get places
        $places = $searchTerm ? $this->placeRepository->search($latitude, $longitude, $constraints) : [];
        $this->view->assign('places', $places);
    }

    /**
     * Show a single place
     *
     * @param int $place A single place uid
     */
    public function showAction(int $place)
    {
        // TODO: Implement
    }
}