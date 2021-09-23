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

use craft\log\FileTarget;
use joomkit\mapitapi\services\MapitapiService as MapitapiServiceService;
use joomkit\mapitapi\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use putyourlightson\logtofile\LogToFile;

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

    public static function log($message){
        Craft::getLogger()->log($message, \yii\log\Logger::LEVEL_INFO, 'mapitapi');
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $fileTarget = new FileTarget([
            'logFile' => __DIR__. '/mapitapi.log', // <--- path of the log file
            'categories' => ['mapitapi'] // <--- categories in the file
        ]);
        // include the new target file target to the dispatcher
        Craft::getLogger()->dispatcher->targets[] = $fileTarget;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'mapitapi/default';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'mapitapi/default/do-something';
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
    public function getCpNavItem()
    {
        $parent = parent::getCpNavItem();

        // Allow user to override plugin name in sidebar

            $parent['label'] = "Mapit import";

        return $parent;
        // return array_merge($parent,[
        //     'subnav' => [
        //         'sectionName' => [
        //             'label' => Craft::t('your-plugin', 'Tab Name'),
        //             'url'   => 'your-plugin/section-name'
        //         ]
        //     ]
        // ]);
    }
}
