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

namespace Tollwerk\TwPlaces\Domain\Repository;


use Tollwerk\TwPlaces\Domain\Model\Place;
use Tollwerk\TwRuag\Domain\Model\Product\Ammo;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class PlaceRepository
 * @package Tollwerk\TwPlaces\Domain\Repository
 */
class PlaceRepository extends Repository
{
    /**
     * @var array
     */
    protected $simpleFilterProperties = [
        'country',
    ];

    /**
     * For some query constraints we always want to be able to find by multiple values.
     * So, if a single non-array value gets passed, convert it into an array.
     *
     * @param mixed $value
     * @param mixed $queryBuilder
     *
     * @return array
     */
    protected function arrayValue($value, $queryBuilder)
    {
        if (!is_array($value)) {
            return [$queryBuilder->createNamedParameter($value)];
        }

        $return = [];
        foreach ($value as $arrayKey => $arrayValue) {
            $return[$arrayKey] = $arrayValue; //  $queryBuilder->createNamedParameter($arrayValue, \PDO::PARAM_INT);
        }
        return $return;
    }

    /**
     * @param float|null $latitude
     * @param float|null $longitude
     * @param array $constraints
     * @return mixed[]
     */
    public function search(float $latitude = null, float $longitude = null, array $constraints = [])
    {
        // Get high level query object for getting storage pids etc.
        $query = $this->createQuery();

        // Get low level query builder for building distance based database query
        /** @var  \Doctrine\DBAL\Query\QueryBuilder $concreteQueryBuilder */
        $concreteQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_twplaces_domain_model_place')
            ->createQueryBuilder()
            ->getConcreteQueryBuilder();

        // Create string for querying the distance
        if ($latitude !== null && $longitude !== null) {
            $distanceQuery =
                '(round(
                    6371000 * acos(
                        cos(radians('.$latitude.'))
                        * cos(radians(place.latitude))
                        * cos(radians(place.longitude) - radians('.$longitude.'))
                        + sin(radians('.$latitude.'))
                        * sin(radians(place.latitude))
                        )
                )) AS distance';
        } else {
            $distanceQuery = '0 as distance';
        }

        // Build basic query
        $concreteQueryBuilder->select(
            'place.*',
            $distanceQuery
        )
            ->from('tx_twplaces_domain_model_place', 'place')
            ->where($concreteQueryBuilder->expr()->eq('place.deleted', 0))
            ->andWhere($concreteQueryBuilder->expr()->eq('place.hidden', 0))
            ->groupBy('uid')
            ->orderBy('geocoded', 'DESC')
            ->addOrderBy('distance', 'ASC');

        // Set simple filters with IN (...) constraints
        foreach ($this->simpleFilterProperties as $property) {
            if (!empty($constraints[$property])) {
                $concreteQueryBuilder->andWhere($concreteQueryBuilder->expr()->in('place.'.GeneralUtility::camelCaseToLowerCaseUnderscored($property), $this->arrayValue($constraints[$property], $concreteQueryBuilder)));
            }
        }

        if(!empty($constraints['limit'])) {
            $concreteQueryBuilder->setMaxResults(intval($constraints['limit']));
        }

        // TODO: Respect storage pids
        // TODO: Check domain
        // TODO: Check enable fields
        // TODO: Respoect exclude_countries, include_countries

        $result = $concreteQueryBuilder->execute()->fetchAll();

        // Because the $queryBuilder returns raw results we have to create the objects with dataMapper
        $dataMapper = $this->objectManager->get(DataMapper::class);
        $result = $dataMapper->map(Place::class, $result);

        return $result;

    }
}