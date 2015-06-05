<?php
/**
 * @file      MenuController.php.
 * @date      6/4/2015
 * @time      5:03 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Json;

/* MODEL */
use common\models\Menu;
use common\models\PostType;
use common\models\Taxonomy;
use common\models\MenuItem;
use common\models\Post;
use common\models\Term;

/**
 * MenuController implements the CRUD actions for Menu model.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MenuController extends Controller
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
                        'actions' => ['index', 'update', 'create', 'delete', 'create-menu-item', 'delete-menu-item'],
                        'allow'   => true,
                        'roles'   => ['administrator']
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'           => ['post'],
                    'update'           => ['post'],
                    'create-menu-item' => ['post'],
                    'delete-menu-item' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     *
     * @param null $id
     *
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $model = new Menu();
        // list all post types
        $postTypes = PostType::find()->where(['post_type_smb' => 1])->all();
        // list all taxonomies
        $taxonomies = Taxonomy::find()->where(['taxonomy_smb' => 1])->all();

        // get available menu
        if ($availableMenu = ArrayHelper::map(Menu::find()->all(), 'id', 'menu_title')) {
            if ($id === null && $availableMenu) {
                foreach ($availableMenu as $key => $menu) {
                    $id = $key;
                    break;
                }
            }
            $selectedMenu = $this->findModel($id);
        }

        return $this->render('index', [
            'model'         => $model,
            'availableMenu' => $availableMenu,
            'selectedMenu'  => isset($selectedMenu) ? $selectedMenu : null,
            'postTypes'     => $postTypes,
            'taxonomies'    => $taxonomies,
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Menu model.
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

            if ($menuOrder = Yii::$app->request->post('MenuOrder')) {
                $this->saveMenuItem(Json::decode($menuOrder));
            }

        }
        Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Menu successfully saved.'));
        return $this->redirect(['/menu/index', 'id' => $id]);
    }

    /**
     * Deletes an existing Menu model.
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
     * Create menu item by post
     *
     * @param int $id
     */
    public function actionCreateMenuItem($id)
    {
        if (Yii::$app->request->post('type') === 'link') {
            $model = new MenuItem();
            $model->menu_id = $id;
            if (($model->load(Yii::$app->request->post()) && ($model->menu_id = $id) && ($model->save()))) {
                echo $this->renderPartial('_render-item', ['item' => $model, 'wrapper' => 'true']);
            }
        } else if (Yii::$app->request->post('type') === 'post' && $postIds = Yii::$app->request->post('postIds')) {
            foreach ($postIds as $postId) {
                if ($postModel = $this->findPost($postId)) {
                    $model = new MenuItem();
                    $model->menu_id = $id;
                    $model->menu_label = $postModel->post_title;
                    $model->menu_url = $postModel->url;
                    if ($model->save()) {
                        echo $this->renderPartial('_render-item', ['item' => $model, 'wrapper' => 'true']);
                    }
                }
            }
        } else if (Yii::$app->request->post('type') === 'taxonomy' && $termIds = Yii::$app->request->post('termIds')) {
            foreach ($termIds as $termId) {
                if ($termModel = $this->findTerm($termId)) {
                    $model = new MenuItem();
                    $model->menu_id = $id;
                    $model->menu_label = $termModel->term_name;
                    $model->menu_url = $termModel->url;
                    if ($model->save()) {
                        echo $this->renderPartial('_render-item', ['item' => $model, 'wrapper' => 'true']);
                    }
                }
            }
        }
    }

    /**
     * Delete menu item via ajax post.
     *
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDeleteMenuItem()
    {
        /* @var $child \common\models\MenuItem */
        if ($id = Yii::$app->request->post('id')) {
            $model = $this->findMenuItem($id);
            if ($model && $model->delete()) {
                $children = MenuItem::find()->where(['menu_parent' => $model->id])->all();
                foreach ($children as $child) {
                    $child->menu_parent = $model->menu_parent;
                    $child->save();
                }
            } else {
                throw new NotFoundHttpException('writesdown', 'The requested page does not exist.');
            }
        }
    }

    /**
     * Save menu item recursively based on parent and child.
     *
     * @param     $menuOrder
     * @param int $menuParent
     */
    protected function saveMenuItem($menuOrder, $menuParent = 0)
    {
        foreach ($menuOrder as $key => $order) {
            $menuItem = Yii::$app->request->post('MenuItem')[ $order['id'] ];
            if ($model = $this->findMenuItem($order['id'])) {
                $model->setAttributes($menuItem);
                $model->menu_parent = $menuParent;
                $model->menu_order = $key;
                if ($model->save()) {
                    if (isset($order['items'])) {
                        $this->saveMenuItem($order['items'], $model->id);
                    }
                }
            }
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, it will return false.
     *
     * @param integer $id
     *
     * @return MenuItem|bool|null|static
     */
    protected function findMenuItem($id)
    {
        if (($model = MenuItem::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, it will return false.
     *
     * @param integer $id
     *
     * @return Post|bool|null|static
     */
    protected function findPost($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * Finds the Term model based on its primary key value.
     * If the model is not found, it will return false.
     *
     * @param integer $id
     *
     * @return Term|bool|null|static
     */
    protected function findTerm($id)
    {
        if (($model = Term::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
}
