<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\models\search\Taxonomy as TaxonomySearch;
use common\models\search\Term as TermSearch;
use common\models\Taxonomy;
use common\models\Term;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TaxonomyController implements the CRUD actions for Taxonomy model.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class TaxonomyController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'bulk-action', 'ajax-create'],
                        'allow'   => true,
                        'roles'   => ['administrator'],
                    ],
                    [
                        'actions' => ['view', 'update-term', 'delete-term'],
                        'allow'   => true,
                        'roles'   => ['editor'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'      => ['post'],
                    'bulk-action' => ['post'],
                    'ajax-create' => ['post'],
                    'delete-term' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Taxonomy models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaxonomySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * View single taxonomy, list all Term from it and create a new Term model.
     * If create Term successful, the browser will be redirected to 'view taxonomy' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $term = new Term();
        $searchModel = new TermSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        if ($term->load(Yii::$app->request->post())) {
            $term->taxonomy_id = $id;
            if ($term->save()) {
                return $this->redirect(['/taxonomy/view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model'        => $this->findModel($id),
            'term'         => $term,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Taxonomy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Taxonomy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Taxonomy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Taxonomy model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Bulk action for Taxonomy triggered when button 'Apply' clicked.
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
     * Updates an existing Term on 'view taxonomy' page.
     *
     * @param $id
     * @param $term_id
     *
     * @throws NotFoundHttpException
     * @return string|\yii\web\Response
     * @see actionView
     */
    public function actionUpdateTerm($id, $term_id)
    {
        $term = $this->findTerm($term_id);
        $searchModel = new TermSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        if ($term->load(Yii::$app->request->post())) {
            $term->taxonomy_id = $id;
            if ($term->save()) {
                return $this->redirect(['/taxonomy/view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model'        => $this->findModel($id),
            'term'         => $term,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Delete an existing Term of a taxonomy on 'view taxonomy' page.
     * If deletion is successful, the browser will be redirected to the 'view taxonomy' page.
     *
     * @param integer $id
     * @param integer $term_id
     *
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionDeleteTerm($id, $term_id)
    {
        $this->findTerm($term_id)->delete();

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Create taxonomy via AJAX request on 'create' and 'update post type' page.
     */
    public function actionAjaxCreate()
    {
        $model = new Taxonomy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            echo '<br />';
            echo Html::label(Html::checkbox('taxonomy_ids[]', true, ['value' => $model->id])
                . ' '
                . $model->taxonomy_name);
        }
    }

    /**
     * Finds the Taxonomy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Taxonomy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Taxonomy::findOne($id)) !== null) {
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
     * @return Term the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findTerm($id)
    {
        if (($model = Term::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
