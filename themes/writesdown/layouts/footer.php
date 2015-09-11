<?php
/**
 * @file      footer.php
 * @date      8/23/2015
 * @time      6:48 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Post;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $posts common\models\Post[] */
?>
<footer id="footer-primary">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="widget">
                    <div class="widget-title">
                        <h4>WritesDown</h4>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus
                        mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa
                        quis enim.
                    </p>
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
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label' => '<i class="fa fa-home"></i> Home',
                                'url'   => ['/site/index'],
                            ],
                            [
                                'label'   => '<i class="fa fa-envelope"></i> Contact Us',
                                'url'     => ['/site/contact'],
                            ],
                            [
                                'label'   => '<i class="fa fa-rss"></i> Entries RSS',
                                'url'     => ['/feed'],
                            ],
                            [
                                'label'   => '<i class="fa fa-sitemap"></i> XML Sitemap',
                                'url'     => ['/sitemap'],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</footer>
<footer id="footer-secondary">
    <div class="container">
        <h5>
            Copyright &copy; 2015 WritesDown all right reserved. Powered by
            <a rel="external nofollow" href="http://www.yiiframework.com/">
                Yii Framework
            </a>
        </h5>
    </div>
</footer>
