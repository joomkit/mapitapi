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
            ->orderBy('id')
            ->ids();
        $queue = Craft::$app->getQueue();

        foreach (array_chunk($elementIds, 100) as $ids) {
            $jobId = $queue->push(new Importgeojson(['elementIds' => $ids]));
            Craft::info(
                Craft::t(
                    'mapitapi',
                    'Started import geo json queue job id: {jobId}',
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
//        $elementIds = Craft::$app->db->createCommand()
//            ->select('id')
//            ->from('entries')
//            ->where('sectionId = :sectionId', array(':sectionId' => 34))
//            ->queryColumn();
        $elementIds = Entry::find()
            ->section('openFunding')
            ->orderBy('id')
            ->ids();
        foreach ($elementIds as $id) {
            $element = Entry::find()->anyStatus()->id($id)->one();
            Craft::$app->getElements()->deleteElement($element);
            Craft::info(
                Craft::t(
                    'mapitapi',
                    'Deleted ' . $element->id. ': title '.$element->title ,
                    [
                        'Id' => $element->id,
                        'Title' => $element->title,
                    ]
                ),
                __METHOD__
            );
        }


    }
}
