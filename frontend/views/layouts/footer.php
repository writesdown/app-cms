<?php
/**
 * @file      footer.php.
 * @date      6/4/2015
 * @time      10:24 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
/* MODEL */
use common\models\Option;

?>
<footer id="footer-primary">
    <div class="container">
        <h5>Copyright &copy; <?= date('Y') ?> <?= Html::a(Option::get('sitetitle'), 'http://www.writesdown.com/') ?> All right reserved. <?= Yii::powered() ?>.</h5>
    </div>
</footer>