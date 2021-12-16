<?php
/**
 * mapitapi plugin for Craft CMS 3.x
 *
 * does map stuff
 *
 * @link      https://www.joomkit.co.uk
 * @copyright Copyright (c) 2021 Alan Sparkes
 */

namespace joomkit\mapitapi;

use joomkit\mapitapi\jobs\Importgeojson;
use joomkit\mapitapi\services\MapitapiService as MapitapiServiceService;
use joomkit\mapitapi\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;


use yii\base\Event;

/**
 * Class Mapitapi
 *
 * @author    Alan Sparkes
 * @package   Mapitapi
 * @since     1.0.0
 *
 * @property  MapitapiServiceService $mapitapiService
 */
class Mapitapi extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Mapitapi
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {


        parent::init();
        self::$plugin = $this;
        $this->setComponents([
            'Importgeojson' => Importgeojson::class, // job
            'MapitapiService' => MapitapiService::class, //service
        ]);

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
//                $event->rules['siteActionTrigger1'] = 'mapitapi/default';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                // $event->rules['cpActionTrigger1'] = 'mapitapi/default/do-something';
                $event->rules['mapitapi/default/map-it'] = ['template' => 'mapitapi/results'];
                $event->rules['mapitapi/tools'] = ['template' => 'mapitapi/tools'];
                $event->rules['mapitapi/default/delete'] = ['template' => 'mapitapi/delete'];
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'mapitapi',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'mapitapi/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
