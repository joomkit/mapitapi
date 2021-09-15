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

use joomkit\mapitapi\Mapitapi;

use Craft;
use craft\base\Component;

/**
 * @author    Alan Sparkes
 * @package   Mapitapi
 * @since     1.0.0
 */
class MapitapiService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (Mapitapi::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }
}
