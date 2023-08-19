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
use joomkit\mapitapi\jobs\DeleteFundingData;
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
    public function doBackGroundJob()
    {
        $elementIds = Entry::find()
            ->section('openFunding')
            ->orderBy('id asc')
            ->ids();
        $queue = Craft::$app->getQueue();

//        foreach ($elementIds as $id) {
//            $element = Entry::find()->id($id)->one();
//            echo $element->id;
//            var_dump($element->geojson->value);
////die();
//            if (!$element->geojson->value) {
//                echo "get json"; die();
//            }
//
//        }
//        die();
        foreach (array_chunk($elementIds, 300) as $ids) {
            $jobId = $queue->ttr(220)->push(new Importgeojson(['elementIds' => $ids]));

            Craft::info(
                Craft::t(
                    'mapitapi',
                    'Started import internal geojson queue job id: {jobId}',
                    [
                        'jobId' => $jobId,
                    ]
                ),
                __METHOD__
            );
        }
    }
    public function delete()
    {
        // send to queue
        $queue = Craft::$app->getQueue();
        $elementIds = Entry::find()
            ->section('openFunding')
            ->orderBy('id')
            ->ids();
        foreach (array_chunk($elementIds, 200) as $ids) {

            $jobId = $queue->push(new DeleteFundingData(['elementIds' => $ids]));

            Craft::info(
                Craft::t(
                    'mapitapi',
                    'Deleted batch of open funding' ,
                    [
                        'jobId' => $jobId
                    ]
                ),
                __METHOD__
            );
        }


    }
}
