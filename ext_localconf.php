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

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

call_user_func(
    function() {
        // Register fluid ViewHelper namespace
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['places'] = ['Tollwerk\\TwPlaces\\ViewHelpers'];

        // Register custom classes for TCA column 'eval' TODO: Fix. Right now, the eval is not executed
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Tollwerk\TwPlaces\Evaluation\CoordinateEvaluation::class] = '';

        // Register plugins
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwPlaces',
            'Search',
            [\Tollwerk\TwPlaces\Controller\PlaceController::class => 'searchForm, search, show'],
            [\Tollwerk\TwPlaces\Controller\PlaceController::class => 'searchForm, search']
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwPlaces',
            'List',
            [\Tollwerk\TwPlaces\Controller\PlaceController::class => 'list, show'],
            [\Tollwerk\TwPlaces\Controller\PlaceController::class => 'list']
        );
    }
);

