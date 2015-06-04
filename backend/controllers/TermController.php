<?php
/**
 * @file      TermController.php.
 * @date      6/4/2015
 * @time      5:11 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/* MODEL */
use common\models\Term;
use common\models\TermRelationship;

/**
 * TermController implements the CRUD actions for Term model.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class TermController extends Controller
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
                        'actions' => ['ajax-create-hierarchical', 'ajax-create-non-hierarchical', 'bulk-action'],
                        'allow'   => true,
                        'roles'   => ['editor'],
                    ],
                    [
                        'actions' => ['ajax-search'],
                        'allow'   => true,
                        'roles'   => ['subscriber'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'ajax-create-hierarchical'     => ['post'],
                    'ajax-create-non-hierarchical' => ['post'],
                    'bulk-action'                  => ['post'],
                    'ajax-search'                  => ['post']
                ],
            ],
        ];
    }


    /**
     * Create term taxonomy non-hierarchical
     */
    public function actionAjaxCreateHierarchical()
    {
        $term = new Term();
        $termRelationship = new TermRelationship();

        if ($term->load(Yii::$app->request->post())) {
            if ($termRelationship->load(Yii::$app->request->post()) && $termRelationship->post_id) {
                $term->term_count = 1;
                if ($term->save()) {
                    $termRelationship->term_id = $term->id;
                    if ($termRelationship->save()) {
                        echo '<br />';
                        echo Html::label(Html::checkbox('termIds', true, ['value' => $term->id]) . $term->term_name);
                    }
                }
            } else {
                if ($term->save()) {
                    echo '<br />';
                    echo Html::label(Html::checkbox('termIds', true, ['value' => $term->id]) . $term->term_name);
                }
            }
        }
    }


    /**
     * Create term for non-hierarchical taxonomy via selectize box.
     *
     * @return string
     */
    public function actionAjaxCreateNonHierarchical()
    {
        $model = new Term();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['id' => $model->id, 'term_name' => $model->term_name];
        }

        return [];
    }


    /**
     * Ajax search for term and return json.
     *
     * @return string
     */
    public function actionAjaxSearch()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($term = Yii::$app->request->post('Term')) {
            $model = Term::find()->select(['id', 'term_name'])->where(['like', 'term_name', $term['term_name']])->andWhere(['taxonomy_id' => $term['taxonomy_id']])->limit('10')->all();
            if ($model) {
                return ($model);
            }

        }

        return [];
    }

    /**
     * Bulk action for term on taxonomy view
     *
     * @see \backend\controllers\TaxonomyController::actionView
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') == 'delete') {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->delete();
            }
        }
    }

    /**
     * Finds the Term model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Term the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Term::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}