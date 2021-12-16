<?php
/**
 * mapitapi plugin for Craft CMS 3.x
 *
 * does map stuff
 *
 * @link      https://www.joomkit.co.uk
 * @copyright Copyright (c) 2021 Alan Sparkes
 */

namespace joomkit\mapitapi\services;

use craft\elements\Entry;
use joomkit\mapitapi\jobs\Importgeojson;
use joomkit\mapitapi\Mapitapi;

use Craft;
use craft\base\Component;

/**
 * @author    Alan Sparkes
 * @package   Mapitapi
 * @since     1.0.0
 */
class DeleteOpenFundingRoundsService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function delete()
    {
        echo '<pre>';
        echo "Delete rounds";
        echo '</pre>';
    }
}
