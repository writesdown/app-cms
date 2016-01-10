<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use frontend\widgets\RenderWidget;

/* @var $this yii\web\View */
/* @var $posts common\models\Post[] */
?>
<footer id="footer-primary">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <?= RenderWidget::widget([
                    'location' => 'footer-left',
                    'config'   => [
                        'beforeWidget' => '<div class="widget">',
                        'afterWidget'  => '</div>',
                        'beforeTitle'  => '<div class="widget-title"> <h4>',
                        'afterTitle'   => '</div></h4>',
                    ],
                ]) ?>

            </div>
            <div class="col-md-3">
                <?= RenderWidget::widget([
                    'location' => 'footer-middle',
                    'config'   => [
                        'beforeWidget' => '<div class="widget">',
                        'afterWidget'  => '</div>',
                        'beforeTitle'  => '<div class="widget-title"> <h4>',
                        'afterTitle'   => '</div></h4>',
                    ],
                ]) ?>

            </div>
            <div class="col-md-3">
                <?= RenderWidget::widget([
                    'location' => 'footer-right',
                    'config'   => [
                        'beforeWidget' => '<div class="widget">',
                        'afterWidget'  => '</div>',
                        'beforeTitle'  => '<div class="widget-title"> <h4>',
                        'afterTitle'   => '</div></h4>',
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</footer>
<footer id="footer-secondary">
    <div class="container">
        <h5>
            Copyright &copy; 2015 <a href="http://www.writesdown.com/">WritesDown</a> all right reserved.
            Powered by <a rel="external nofollow" href="http://www.yiiframework.com/">Yii Framework</a>
        </h5>
    </div>
</footer>
<?php $this->registerJs('(function($){$(".widget ul").addClass("nav")})(jQuery);', $this::POS_END) ?>
