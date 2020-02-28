<?php
/**
 * Created by PhpStorm.
 * User: lucifer
 * Date: 27.02.2020
 * Time: 21:32
 */

namespace Tollwerk\TwPlaces\Utility;


use Doctrine\DBAL\FetchMode;
use SJBR\StaticInfoTables\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FilterUtility
 * @package Tollwerk\TwPlaces\Utility
 */
class FilterUtility implements SingletonInterface
{
    /**
     * List of valid filter properties
     *
     * @var array
     */
    protected $filters = [
        'country'
    ];

    /**
     * @return string
     */
    protected function getEmptyOption() {
        return '---';
    }

    /**
     * @return array
     */
    public function getOptionsForCountry() : array
    {
        // Initialize some objects
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_twplaces_domain_model_place')->createQueryBuilder();
        $queryBuilder->select('country.uid')
            ->from('tx_twplaces_domain_model_place', 'place')
            ->innerJoin(
                'place',
                'static_countries',
                'country',
                $queryBuilder->expr()->eq('place.country', $queryBuilder->quoteIdentifier('country.uid'))
            )
            ->groupBy('country.uid');



        $countries = $queryBuilder->execute()->fetchAll(FetchMode::COLUMN);
        $options = [0 => $this->getEmptyOption()];
        foreach($countries as $countryUid) {
            $countryNameLocalized = LocalizationUtility::translate(['uid' => $countryUid], 'static_countries');
            $options[$countryUid] = $countryNameLocalized;
        }

        if(class_exists('Collator')) {
            $c = new \Collator('de_DE'); // TODO: Get from current frontent language
            $c->asort($options);
        } else {
            natsort($options);
        }
        return $options;
    }
}