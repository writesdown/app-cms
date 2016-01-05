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
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TermController implements the CRUD actions for Term model.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
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
                    'ajax-search'                  => ['post'],
                ],
            ],
        ];
    }

    /**
     * Create a new Term model for hierarchical Taxonomy through AJAX request.
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
                        echo Html::label(Html::checkbox('termIds[]', true, ['value' => $term->id]) . $term->term_name);
                    }
                }
            } elseif ($term->save()) {
                echo '<br />';
                echo Html::label(Html::checkbox('termIds[]', true, ['value' => $term->id]) . $term->term_name);
            }
        }
    }

    /**
     * Create a new Term for non-hierarchical Taxonomy through Selectize box.
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
     * Search Term through Ajax with JSON as response.
     *
     * @return string
     */
    public function actionAjaxSearch()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($term = Yii::$app->request->post('Term')) {
            $model = Term::find()
                ->select(['id', 'term_name'])
                ->where(['like', 'term_name', $term['term_name']])
                ->andWhere(['taxonomy_id' => $term['taxonomy_id']])
                ->limit('10')
                ->all();
            if ($model) {
                return ($model);
            }
        }

        return [];
    }

    /**
     * Bulk action for Term triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
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
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
