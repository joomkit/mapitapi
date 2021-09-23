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

use putyourlightson\logtofile\LogToFile;

/**
 * @author    Alan Sparkes
 * @package   Mapitapi
 * @since     1.0.0
 */
class Importgeojson extends BaseJob
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $areaId;
    public $geoJson;
    private $mapAPIkey;
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {


        // Do work here
        $query = Entry::find();
        if (!empty($this->criteria)) {
            Craft::configure($query, $this->criteria);
        }
        $query
            ->section('openFunding')
            ->orderBy('id')
            ->limit(null)
            ->all();

        $totalElements = $query->count();
        $currentElement = 0;

        try {
            foreach ($query->each() as $element) {

                $this->setProgress($queue, $currentElement++ / $totalElements);
                $match = Entry::find()->section('mapitOlfData')->ons($element->lsoacode)->one(); //one because we want one entry returned and lsoanames are unique

                if ($match) {
                    // if we have a match then call the mapit api with the area id
                    $geoData = $this->fetchGeoJson($match->mapitAreaId);

                    $element->setFieldValue('mapitAreaId', $match->mapitAreaId);
                    $element->setFieldValue('geojson', $geoData);

                    if (!Craft::$app->getElements()->saveElement($element)) {
                        Craft::error(
                            ' || Couldnt save funding entry: ' . $element->id . ':' . $element->title . '| lsoa:' . $element->lsoacode,
                            __METHOD__
                        );
                    }else{
                        Craft::debug(
                            Craft::t(
                                'mapitapi',
                                'Sleeping for 2 secs ..saved funding entry: ' . $element->id . ':' . $element->title . '| lsoa:' . $element->lsoacode
                            ),
                            __METHOD__
                        );
                        sleep(1);
                    }
                }

                //}
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


        $mapAPIkey = "IJvGFcalAXdjcuqfoIIYiFoiVyXLGjjg6mKoHb0F";

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
