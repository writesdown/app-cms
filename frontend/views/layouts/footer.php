<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;
use yii\helpers\Html;

?>
<footer id="footer-primary">
    <div class="container">
        <h5>
            Copyright &copy; <?= date('Y') ?>
            <?= Html::a(Option::get('sitetitle'), 'http://www.writesdown.com/') ?>
            All right reserved. <?= Yii::powered() ?>.
        </h5>
    </div>
</footer>