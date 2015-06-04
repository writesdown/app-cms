<?php
/**
 * @file      TermRelationshipController.php.
 * @date      6/4/2015
 * @time      5:12 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/* MODEL */
use common\models\Term;
use common\models\TermRelationship;

/**
 * TermRelationshipController implements the CRUD actions for TermRelationship model.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class TermRelationshipController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['ajax-change-hierarchical', 'ajax-create-non-hierarchical', 'ajax-delete-non-hierarchical'],
                        'allow'   => true,
                        'roles'   => ['subscriber']
                    ],
                ],
            ],

            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'ajax-change-hierarchical'     => ['post'],
                    'ajax-create-non-hierarchical' => ['post'],
                    'ajax-delete-non-hierarchical' => ['post'],
                ],
            ],

        ];
    }

    /**
     * If user change the value of checkbox when updating a post, it will trigger this action.
     * If the checkbox checked it will trigger addItem while if checked will trigger remItem (remove term relationship).
     *
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAjaxChangeHierarchical()
    {
        if (Yii::$app->request->post('action') === 'addItem') {
            $model = new TermRelationship();
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                if ($term = $this->findTerm($model->term_id)) {
                    $term->term_count++;
                    $term->save();
                }
            }
        } else if (Yii::$app->request->post('action') === 'remItem' && $termRelationship = Yii::$app->request->post('TermRelationship')) {
            $model = $this->findModel($termRelationship['post_id'], $termRelationship['term_id']);
            if ($model->delete()) {
                if ($term = $this->findTerm($model->term_id)) {
                    $term->term_count--;
                    $term->save();
                }
            }
        }
    }

    /**
     * Ajax to create term-relationship triggered when the user add new item in selectize box.
     */
    public function actionAjaxCreateNonHierarchical()
    {
        $model = new TermRelationship();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($term = $this->findTerm($model->term_id)) {
                $term->term_count++;
                $term->save();
            }
        }
    }

    /**
     * Delete term-relationship for non-hierarchical taxonomy which is triggered when the user remove item in selectize
     * box.
     *
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAjaxDeleteNonHierarchical()
    {
        if ($termRelationship = Yii::$app->request->post('TermRelationship')) {
            $model = $this->findModel($termRelationship['post_id'], $termRelationship['term_id']);
            if ($model->delete()) {
                if ($term = $this->findTerm($model->term_id)) {
                    $term->term_count--;
                    $term->save();
                }
            }
        }
    }

    /**
     * Finds the TermRelationship model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $post_id
     * @param integer $term_id
     *
     * @return TermRelationship the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($post_id, $term_id)
    {
        if (($model = TermRelationship::findOne(['post_id' => $post_id, 'term_id' => $term_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Term model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Term|false the loaded model
     */
    protected function findTerm($id)
    {
        if ($model = Term::findOne($id)) {
            return $model;
        } else {
            return false;
        }
    }
}