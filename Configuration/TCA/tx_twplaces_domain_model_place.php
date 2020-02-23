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
use Tollwerk\TwPlaces\Domain\Model\Place;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Resource\File;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'default_sortby' => 'sorting ASC',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden, starttime, endtime',
        ],
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'searchFields' => 'name, given_name, family_name',
        'iconfile' => 'EXT:tw_places/Resources/Public/Icons/Backend/Location2.svg',
        'type' => 'type',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title',
    ],
    'palettes' => [
        'name_type' => ['showitem' => 'name, type'],
        'person' => ['showitem' => 'given_name, family_name'],
        'lat_long' => ['showitem' => 'latitude, longitude'],
        'default_restrictions' => ['showitem' => 'hidden, starttime, endtime'],
        'address_1' => ['showitem' => 'country, region, state'],
        'address_2' => ['showitem' => 'postal_code, city, street_address'],
        'contact' => ['showitem' => 'phone, fax, email, url'],
    ],
    'types' => [
        Place::TYPE_LOCATION => [
            'showitem' => TcaUtility::createShowitemString([
                'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general' => [
                    ['name_type'],
                    ['lat_long'],
                    'description',
                    'image',
                ],
                'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access' => [
                    ['default_restrictions'],
                ],
            ]),
        ],
        Place::TYPE_ADDRESS => [
            'showitem' => TcaUtility::createShowitemString([
                'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general' => [
                    ['name_type'],
                    ['lat_long'],
                    'description',
                    ['address_1'],
                    ['address_2'],
                    'image',
                ],
                'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access' => [
                    ['default_restrictions'],
                ],
            ]),
        ],
        Place::TYPE_CONTACT => [
            'showitem' => TcaUtility::createShowitemString([
                'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general' => [
                    ['name_type'],
                    ['person'],
                    ['lat_long'],
                    'description',
                    ['contact'],
                    ['address_1'],
                    ['address_2'],
                    'image',

                ],
                'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access' => [
                    ['default_restrictions'],
                ],
            ]),
        ],
    ],
    'columns' => [
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
        'type' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => Place::TYPE_ADDRESS,
                'items' => [
                    ['', 0],
                    [
                        'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.type.' . Place::TYPE_LOCATION,
                        Place::TYPE_LOCATION
                    ],
                    [
                        'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.type.' . Place::TYPE_ADDRESS,
                        Place::TYPE_ADDRESS
                    ],
                    [
                        'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.type.' . Place::TYPE_CONTACT,
                        Place::TYPE_CONTACT
                    ],
                ],
            ],
        ],
        'name' => [
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.name',
            'config' => [
                'type' => 'input',
                'size' => 32,
                'eval' => 'trim'
            ],
        ],
        'given_name' => [
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.given_name',
            'config' => [
                'type' => 'input',
                'size' => 32,
                'eval' => 'trim'
            ],
        ],
        'family_name' => [
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.family_name',
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
                'eval' => 'required,' . CoordinateEvaluation::class,
            ]
        ],
        'longitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.longitude',
            'config' => [
                'type' => 'input',
                'size' => 16,
                'eval' => 'required,' . CoordinateEvaluation::class
            ]
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
                'enableRichtext' => true,
            ]
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tw_places_domain_model_product.spotlight_image',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'spotlight_image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference',
                        'fileUploadAllowed' => false,
                        'collapseAll' => true,
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                            ],
                            File::FILETYPE_TEXT => [
                                'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                            ],
                            File::FILETYPE_IMAGE => [
                                'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                            ],
                            File::FILETYPE_AUDIO => [
                                'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                            ],
                            File::FILETYPE_VIDEO => [
                                'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                            ],
                            File::FILETYPE_APPLICATION => [
                                'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                            ]
                        ],
                    ],

                    'maxitems' => 1,
                    'minitems' => 0
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'postal_code' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.postal_code',
            'config' => [
                'type' => 'input',
            ]
        ],
        'country' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.country',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['DE', 'DE'],
                    ['US', 'US']
                ]
            ],
        ],
        'region' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.region',
            'config' => [
                'type' => 'input',
            ]
        ],
        'state' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.state',
            'config' => [
                'type' => 'input',
            ]
        ],
        'city' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.city',
            'config' => [
                'type' => 'input',
            ]
        ],
        'street_address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.street_address',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
            ]
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.email',
            'config' => [
                'type' => 'input',
            ]
        ],
        'phone' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.phone',
            'config' => [
                'type' => 'input',
            ]
        ],
        'fax' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.fax',
            'config' => [
                'type' => 'input',
            ]
        ],
        'url' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tw_places/Resources/Private/Language/locallang_db.xlf:tx_twplaces_domain_model_place.url',
            'config' => [
                'type' => 'input',
            ]
        ],
        'distance' => [
            'config' => [
                'type' => 'passthrough'
            ],
        ],
    ],
];


