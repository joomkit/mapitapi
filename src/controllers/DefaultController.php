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
use craft\queue\BaseJob;
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
        // $this->requirePostRequest();
        // $request = Craft::$app->getRequest();

        // $body = Craft::$app->request->getRawBody();
        // var_dump($body);
        Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_CP);

        
        // $result = 'Welcome to the DefaultController mapit() method';
        // // return $result;
        // //Craft::$app->getUrlManager()->setRouteParams(['variable' => $result]);
        // // return null;
        // // // 
        
        // return $this->renderTemplate('results', $result);

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

        // Craft::$app->getUrlManager()->setRouteParams([
        //     'variable' => $myVariable
        // ]);
    
        // return null;
    
        $variables = array( 'res' => $OLFentries[0]);
        return $this->renderTemplate('mapitapi/results', $variables);
//         return $this->renderTemplate('mapit-api2entry/results', $variables);

        // return $OLFentries[0];
        // $q = (new \craft\db)
        // $query = (new \craft\db\Query())
        // ->select('openFunding')
        // ->indexBy('lsoaname')
        // ->ons($entry->lsoacode)
        // ->all();        

        
        // return $query;
        // $match = Entry::find()->title('E01013372')->section('mapitOlfData')->all();

        // foreach ($entries as $entry) {
  
        //     $match = Entry::find()->section('mapitOlfData')->ons($entry->lsoacode)->all();
      
        //     // foreach ($match as $m){
        //     foreach (\craft\helpers\Db::each($match) as $m) {
        //         echo $entry->lsoacode. ":: has areaid:". $m->mapitAreaId. "<br>";
        //     }
        // }
       
        // $res = "ran query";
        // return $res;
        // $data = craft\helpers\Json::decode($body);
        // return $data;
        
    }

    public function updateEntriesWithCustomInfo()
        {
            /** @var craft\services\Elements $elementService */
            $elementService = Craft::$app->elements;

            // add conditions here
        
            

            $entries = Entry::find()->limit(1500)->all();

            foreach ($entries as $entry) {
                // update your field
                $entry->setFieldValue('my_custom_field', 'some_value');
                $success = $elementService->saveElement($entry);
                if (!$success) {
                    // saving failed, log error or abort
                }
            }
        }
}
