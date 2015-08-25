<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      12:03 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $themes [] */
/* @var $installed string */

$this->title = Yii::t('writesdown', 'Theme');
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="theme">
        <div id="nav-tabs-custom" class="nav-tabs-custom">
            <?= $this->render('_navigation'); ?>
            <div class="tab-content">
                <div class="row">
                    <?php
                    foreach ($themes as $dir=>$theme) {
                        echo $this->render('_theme-thumbnail', [
                            'theme' => $theme,
                            'installed'=> $installed
                        ]);
                    } ?>
                </div>
            </div>
        </div>
    </div>

<?php
Modal::begin([
    'header' => Yii::t('writesdown', 'Detail Theme'),
    'id'     => 'modal-for-theme-detail',
    'size'   => Modal::SIZE_LARGE
]);

Modal::end();

$this->registerJs('
$(".btn-detail").on("click", function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).data("ajax-detail"),
        data: {"theme": $(this).data("theme")},
        success: function(response){
            $("#modal-for-theme-detail").find(".modal-body").html(response);
        }
    });
    $("#modal-for-theme-detail").modal("show");
});
');
