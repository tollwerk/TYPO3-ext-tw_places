<?php
/**
 * RWS Relaunch
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwPlaces
 * @subpackage Tollwerk\TwPlaces\Scheduler\Task
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

namespace Tollwerk\TwPlaces\Scheduler\Task;


use Tollwerk\TwGeo\Utility\GeoUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * GeocodePlacesTask
 *
 * Try to set latitude and longitude of Place records
 * by geocoding the combined fields country, street_address, postalcode and city
 *
 * @package    Tollwerk\TwPlaces
 * @subpackage Tollwerk\TwPlaces\Scheduler\Task
 */
class GeocodePlacesTask extends AbstractTask
{
    /** @var QueryBuilder */
    protected $queryBuilder = null;

    /**
     * Get place records for geocoding.
     * Right now, just return those without latitude or longitude set
     *
     * TODO: Make it possible to update the records continually by checking tstamp etc.
     *
     * @param int $limit
     * @return array
     */
    protected function getPlaceRecords(int $limit = 10): array
    {
        $this->queryBuilder
            ->select(
                'place.uid',
                'place.street_address',
                'place.postal_code',
                'place.city',
                'country.cn_iso_2'
            )
            ->from('tx_twplaces_domain_model_place', 'place')
            ->leftJoin(
                'place',
                'static_countries',
                'country',
                'place.country = country.uid'
            )
            ->where($this->queryBuilder->expr()->eq('place.deleted', 0))
            ->orderBy('geocoded', 'ASC')
            ->addOrderBy('longitude', 'ASC')
            ->addOrderBy('tstamp', 'ASC')
            ->setMaxResults($limit);

        return $this->queryBuilder->execute()->fetchAll();
    }

    /**
     * @param int $uid
     * @param float|null $latitude
     * @param float|null $longitude
     */
    protected function updatePlace(int $uid, float $latitude = null, float $longitude = null)
    {
        $this->queryBuilder
            ->update('tx_twplaces_domain_model_place', 'place')
            ->where($this->queryBuilder->expr()->eq('place.uid', $uid))
            ->set('place.latitude', $latitude)
            ->set('place.longitude', $longitude)
            ->set('place.tstamp', time())
            ->set('place.geocoded', 1)
            ->execute();;
    }

    /**
     * This is the main method that is called when the task is executed
     *
     * @return bool Returns TRUE on successful execution, FALSE on error
     */
    public function execute()
    {
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_twplaces_domain_model_product_place')->createQueryBuilder();

        /** @var GeoUtility $geoUtility */
        $geoUtility = GeneralUtility::makeInstance(GeoUtility::class);
        $places = $this->getPlaceRecords();

        foreach ($places as $place) {
            $geocodeString = implode(', ', [
                $place['street_address'],
                $place['postal_code'].' '.$place['city'],
                $place['cn_iso_2'],
            ]);

            $position = $geoUtility->geocode($geocodeString);
            debug([
                $place,
                $geocodeString,
                $position,
            ]);


            $this->updatePlace(
                $place['uid'],
                $position ? $position->getLatitude() : null,
                $position ?  $position->getLongitude() : null
            );


            // Don't overuse the geocoding APIs!
            // especially when using free public projects like https://nominatim.openstreetmap.org/
            sleep(0.25);
        }
        return true;
    }
}