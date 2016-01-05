<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\models\Term;
use common\models\TermRelationship;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TermRelationshipController implements the CRUD actions for TermRelationship model.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
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
                        'actions' => [
                            'ajax-change-hierarchical',
                            'ajax-create-non-hierarchical',
                            'ajax-delete-non-hierarchical',
                        ],
                        'allow'   => true,
                        'roles'   => ['subscriber'],
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
     * If user change the value of checkbox when updating a post, this action will be triggered.
     * If the checkbox checked it will trigger addItem (create TermRelationship) while if unchecked will trigger
     * remItem (remove TermRelationship).
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
                    $term->updateAttributes(['term_count' => $term->term_count++]);
                }
            }
        } elseif (Yii::$app->request->post('action') === 'remItem'
            && $termRelationship = Yii::$app->request->post('TermRelationship')
        ) {
            $model = $this->findModel($termRelationship['post_id'], $termRelationship['term_id']);
            if ($model->delete()) {
                if ($term = $this->findTerm($model->term_id)) {
                    $term->updateAttributes(['term_count' => $term->term_count--]);
                }
            }
        }
    }

    /**
     * Create new TermRelationship model through AJAX via Seletize box on 'create' and 'update post' page.
     */
    public function actionAjaxCreateNonHierarchical()
    {
        $model = new TermRelationship();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($term = $this->findTerm($model->term_id)) {
                $term->updateAttributes(['term_count' => $term->term_count++]);
            }
        }
    }

    /**
     * Delete TermRelationship for non-hierarchical Taxonomy
     * which is triggered when the user remove item in Selectize box.
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
                    $term->updateAttributes(['term_count' => $term->term_count--]);
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
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
        }

        return false;
    }
}
