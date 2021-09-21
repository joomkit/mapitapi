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


use joomkit\mapitapi\Mapitapi;

use Craft;
use craft\queue\BaseJob;

use craft\db\Paginator;
use craft\elements\Entry;
use craft\elements\db\ElementQuery;
use craft\helpers\App;


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
    public $someAttribute = 'Some Default';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        // Do work here
        $entries = Entry::find()
            ->section('openFunding')
            ->orderBy('lsoaname')
            ->limit(null)
            ->all();

        $OLFentries = Entry::find()
            ->section('mapitOlfData')
            ->orderBy('title')
            ->limit(3)
            ->all();
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function defaultDescription(): string
    {
        return Craft::t('mapitapi', 'Importgeojson');
    }
}
