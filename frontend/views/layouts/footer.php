<?php
/**
 * @file      footer.php.
 * @date      6/4/2015
 * @time      10:24 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* MODEL */
use common\models\Option;
use common\models\Post;

?>
<footer id="footer-primary">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="widget">
                    <div class="widget-title">
                        <h4>About</h4>
                    </div>
                    <div class="widget-text">
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus
                        mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa
                        quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget">
                    <div class="widget-title">
                        <h4>Recent Posts</h4>
                    </div>
                    <?php
                    $items = [];
                    $posts = Post::find()->innerJoinWith(['postType'])->andWhere(['post_status' => 'publish'])->andWhere(['post_type_name' => 'post'])->orderBy(['id' => SORT_DESC])->limit(5)->all();
                    foreach ($posts as $post) {
                        $items[ $post->id ]['label'] = $post->post_title;
                        $items[ $post->id ]['url'] = $post->url;
                    }
                    if ($items) {
                        echo Nav::widget([
                            'items'   => $items,
                            'options' => ['class' => 'nav'],
                        ]);
                    }
                    ?>
                </div>

            </div>
            <div class="col-md-3">
                <div class="widget">
                    <div class="widget-title">
                        <h4>About Us</h4>
                    </div>
                    <?= Nav::widget([
                        'items'   => [
                            ['label' => 'Home', 'url' => Yii::$app->homeUrl],
                            ['label' => 'About Us', 'url' => ['post/view', 'id' => '1']],
                            ['label' => 'Privacy Policy', 'url' => ['post/view', 'id' => 1]],
                            ['label' => 'Sitemap', 'url' => ['/sitemap']],
                            ['label' => 'Entries RSS', 'url' => ['/feed']],
                        ],
                        'options' => ['class' => 'nav'],
                    ]); ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget">
                    <div class="widget-title">
                        <h4>Follow Us</h4>
                    </div>
                    <?php ActiveForm::begin([
                        'action'  => 'http://feedburner.google.com/fb/a/mailverify',
                        'options' => [
                            'target'   => 'popupwindow',
                            'onsubmit' => new JsExpression('window.open("http://feedburner.google.com/fb/a/mailverify?uri=Your Feed ID", "popupwindow", "scrollbars=yes,width=550,height=520");return true')
                        ]
                    ]); ?>

                    <div class="form-group input-group">
                        <?= Html::label('<span class="glyphicon glyphicon-envelope"></span>', 'email-feedburner', ['class' => 'input-group-addon', 'style' => 'background: #f0ad4e; border: #f0ad4e; color: #FFF']); ?>
                        <?= Html::input('email', 'email', null, ['id' => 'email-feedburner', 'placeholder' => Yii::t('writesdown', 'Your email here'), 'class' => 'form-control']); ?>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('<span class="glyphicon glyphicon-fire"></span> ' . Yii::t('writesdown', 'Subscribe'), ['class' => 'btn btn-warning form-control', 'value' => 'Subscribe']); ?>
                    </div>

                    <?= Html::hiddenInput('uri', 'Your Feed ID'); ?>

                    <?= Html::hiddenInput('loc', 'en_US'); ?>

                    <?php ActiveForm::end(); ?>

                    <?= Nav::widget([
                        'items'        => [
                            ['label' => '<i class="fa fa-facebook-official"></i> Facebook', 'url' => 'http://www.facebook.com', 'options' => ['class' => 'facebook']],
                            ['label' => '<i class="fa fa-twitter"></i> Twitter', 'url' => 'http://www.twitter.com', 'options' => ['class' => 'twitter']],
                            ['label' => '<i class="fa fa-google-plus"></i> Google+', 'url' => 'http://plus.google.com', 'options' => ['class' => 'google-plus']],
                        ],
                        'encodeLabels' => false,
                        'options'      => ['class' => 'social-bookmarks nav'],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</footer>
<footer id="footer-secondary" class="footer">
    <div class="container">
        <p class="pull-left">Copyright &copy; <?= Option::get('sitetitle') ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>