<?php
/**
 * mapitapi plugin for Craft CMS 3.x
 *
 * def
 *
 * @link      https://www.joomkit.co.uk
 * @copyright Copyright (c) 2021 Alan Sparkes
 */

namespace joomkit\mapitapi\jobs;


use craft\console\Application as ConsoleApplication;
use craft\db\QueryAbortedException;
use craft\errors\ElementNotFoundException;
use yii\base\ErrorException;
use joomkit\mapitapi\Mapitapi;
use craft\base\Component;
use Craft;
use craft\queue\BaseJob;

use craft\db\Paginator;
use craft\elements\Entry;
use craft\elements\db\ElementQuery;
use craft\helpers\App;
use yii\base\Exception;


/**
 * @author    Alan Sparkes
 * @package   Mapitapi
 * @since     1.0.0
 */
class Importgeojson extends \craft\queue\BaseJob
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $areaId;
    public $geoJson;
    public $elementIds;
    private $mapAPIkey;
    // Public Methods
    // =========================================================================


    /**
     * @inheritdoc
     */
    public function execute($queue) :void
    {

        $elementIds = $this->elementIds ?? [];
        $totalElements = count($elementIds);
        $currentElement = 0;
        foreach ($elementIds as $id) {
            $element = Entry::find()->id($id)->one();
            //if (!$element) continue;
            // process the current element …
            try {
                if (!$element->geojson->value) {

                    $this->setProgress($queue, $currentElement++ / $totalElements);

                    $match = Entry::find()->section('imdData')->lsoacode($element->lsoacode)->one(); //one because we want one entry returned and lsoanames are unique

                    if ($match) {

                        $geoData = $match->geojson;
                        $element->setFieldValue('mapitAreaId', $match->mapitAreaId); // probably redundant but will leave as reference for backtracking if areas change and we need to access mySociety for updates
                        $element->setFieldValue('geojson', $geoData);

                        if (!Craft::$app->getElements()->saveElement($element)) {
                            Craft::error(
                                'mapitapi || Couldnt save funding entry: ' . $element->id . ':' . $element->title . '| lsoa:' . $element->lsoacode,
                                __METHOD__
                            );
                        }
                        Craft::info(
                            Craft::t(
                                'mapitapi',
                                'Sleeping for 1 secs ..saved funding entry: ' . $element->id . ':' . $element->title . '| lsoa:' . $element->lsoacode . '| lsoa:' . $match->mapitAreaId
                            ),
                            __METHOD__
                        );
//                            // sleep 1 seconds as API implements 1 sec rate limit
//                            sleep(1);

                    } else {
                        Craft::info(
                            Craft::t(
                                'mapitapi',
                                'Could not find lsoacode in OLF data: ' . $element->id . ':' . $element->title . '| lsoa:' . $element->lsoacode
                            ),
                            __METHOD__
                        );
                    }

                }

            } catch (Exception $e) {
                echo '[error]: '
                    . $e->getMessage()
                    . 'exception while processing '
                    . $currentElement . '/' . $totalElements
                    . ' - processing asset: ' . $element->title
                    . ' from field: ' . $element->lsoacode . PHP_EOL;

            }
        }


    }

//    public function saveElements($element){
//        try {
//            Craft::$app->getElements()->saveElement($element) ;
//        } catch (Exception $e) {
//        echo '[error]: '
//        . $e->getMessage()
//        . ' while processing ' . $element->title
//        . ' from field: ' . $element->lsoacode . PHP_EOL;
//
//        }
//}

    public function fetchGeoJson($areaId)
    {


//        $mapAPIkey = "IJvGFcalAXdjcuqfoIIYiFoiVyXLGjjg6mKoHb0F"; // pht's 'GGIvcJdKOseHzXnZfNEiJEyDCtNptcpsEXyaVXKy'
        $mapAPIkey = "GGIvcJdKOseHzXnZfNEiJEyDCtNptcpsEXyaVXKy";
        $baseUri = 'https://mapit.mysociety.org';

        $url = $baseUri . '/area/' . $areaId . '.geojson?api_key=' . $mapAPIkey;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

//        var_dump($resp);

        return $resp;

    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function defaultDescription(): string
    {
        return Craft::t('mapitapi', 'Import geojson from MapitAPI');
    }
}
