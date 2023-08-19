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
class DeleteFundingData extends \craft\queue\BaseJob

{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $areaId;
    public $geoJson;
    public $elementIds;
    private $mapAPIkey;
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function execute($queue): void
    {

        $elementIds = $this->elementIds ?? [];
        $totalElements = count($elementIds);
//        $currentElement = 0;

        foreach ($elementIds as $id) {
//            $this->setProgress(
//                $queue,
//                $i / $totalElements,
//                Craft::t('app', '{step, number} of {total, number}', [
//                    'step' => $i + 1,
//                    'total' => $totalElements,
//                ])
//            );
//            $element = Entry::find()->anyStatus()->id($id)->one();
            //if (!$element) continue;
            // process the current element …
            try {
//                $element = Entry::find()->id($id)->one();
//                Craft::$app->getElements()->deleteElement($element);
                Craft::$app->getElements()->deleteElementById($id, null,null,true);
            } catch (\Throwable $e) {
                Craft::warning("mapitapi delete something went wrong: {$e->getMessage()}", __METHOD__);

            }
        }


    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function defaultDescription(): string
    {
        return Craft::t('mapitapi', 'Deleting open funding data');
    }
}
