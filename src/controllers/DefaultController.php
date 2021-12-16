<?php
/**
 * mapitapi plugin for Craft CMS 3.x
 *
 * does map stuff
 *
 * @link      https://www.joomkit.co.uk
 * @copyright Copyright (c) 2021 Alan Sparkes
 */

namespace joomkit\mapitapi\controllers;

use joomkit\mapitapi\Mapitapi;

use Craft;
use craft\web\Controller;
use craft\web\View;
use craft\elements\Entry;
use craft\db\Query;
use craft\base\Element;
use craft\db\QueryAbortedException;

use craft\helpers\App;

use GuzzleHttp;


use yii\base\Exception;
/**
 * @author    Alan Sparkes
 * @package   Mapitapi
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something', 'map-it'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * @return mixed
     */
    public function actionDoSomething()
    {
        $result = 'Welcome to the DefaultController actionDoSomething() method';

        return $result;
    }


    public function actionMapIt()
    {

        Mapitapi::$plugin->mapitapiService->doBackGroundJob();
//        echo '<pre>';
//        print_r($this->fetchGeoJson($areaId = '67794'));
//        echo '</pre>';
    }

    public function actionDelete()
    {

       // Mapitapi::$plugin->mapitapiService->delete();
        Mapitapi::$plugin->mapitapiService->delete();
//        Mapitapi::getInstance()->deleteOpenFundingRoundsService->delete();

    }

    public function updateEntriesWithCustomInfo()
        {
            /** @var craft\services\Elements $elementService */
//            $elementService = Craft::$app->elements;
//
//            $entries = Entry::find()->limit(1500)->all();
//
//            foreach ($entries as $entry) {
//                // update your field
//                $entry->setFieldValue('my_custom_field', 'some_value');
//                $success = $elementService->saveElement($entry);
//                if (!$success) {
//                    // saving failed, log error or abort
//                }
//            }
        }
}
