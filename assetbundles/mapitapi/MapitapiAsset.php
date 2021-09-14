<?php
/**
 * mapitapi plugin for Craft CMS 3.x
 *
 * does map stuff
 *
 * @link      https://www.joomkit.co.uk
 * @copyright Copyright (c) 2021 Alan Sparkes
 */

namespace joomkit\mapitapi\assetbundles\mapitapi;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Alan Sparkes
 * @package   Mapitapi
 * @since     1.0.0
 */
class MapitapiAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@joomkit/mapitapi/assetbundles/mapitapi/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Mapitapi.js',
        ];

        $this->css = [
            'css/Mapitapi.css',
        ];

        parent::init();
    }
}
