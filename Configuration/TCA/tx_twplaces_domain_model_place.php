<?php
/**
 * tw_places
 *
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

use Tollwerk\TwBase\Utility\TcaUtility;
use Tollwerk\TwPlaces\Evaluation\CoordinateEvaluation;

return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place',
        'label'                    => 'name',
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'cruser_id'                => 'cruser_id',
        'sortby'                   => 'sorting',
        'default_sortby'           => 'sorting ASC',
        'dividers2tabs'            => true,
        'delete'                   => 'deleted',
        'enablecolumns'            => [
            'disabled' => 'hidden, starttime, endtime',
        ],
        'languageField'            => 'sys_language_uid',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'searchFields'             => 'name',
        'iconfile'                 => 'EXT:tw_places/Resources/Public/Icons/Backend/Location2.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title',
    ],
    'palettes'  => [
        'lat_long' => ['showitem' => 'latitude, longitude'],
        'default_restrictions' => ['showitem' => 'hidden, starttime, endtime'],
    ],
    'types'     => [
        '1' => [
            'showitem' => TcaUtility::createShowitemString([
                'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general' => [
                    'name',
                    ['lat_long'],
                ],
                'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access'  => [
                    ['default_restrictions'],
                ],
            ]),
        ],
    ],
    'columns'   => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'sorting' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0]
                ],
                'default' => 0,
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0]
                ],
                'foreign_table' => 'sys_category',
                'foreign_table_where' => 'AND sys_category.uid=###REC_FIELD_l10n_parent### AND sys_category.sys_language_uid IN (-1,0) ORDER BY title ASC',
                'default' => 0
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'name'            => [
            'label'  => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.name',
            'config' => [
                'type' => 'input',
                'size' => 32,
                'eval' => 'trim'
            ],
        ],
        'latitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.latitude',
            'config' => [
                'type' => 'input',
                'size' => 16,
                'eval' => 'required,'.CoordinateEvaluation::class,
            ]
        ],
        'longitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.longitude',
            'config' => [
                'type' => 'input',
                'size' => 16,
                'eval' => 'required,'.CoordinateEvaluation::class
            ]
        ],
    ],
];


