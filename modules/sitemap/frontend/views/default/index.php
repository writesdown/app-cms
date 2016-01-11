<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Url;

/* @var $items array */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<?= '<?xml-stylesheet type="text/xsl" href="' . Url::to(['style']) . '"?>' ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc><![CDATA[<?= Yii::$app->urlManager->hostInfo
            . Url::to(['view', 'type' => 'h', 'slug' => 'home', 'page' => 1]) ?>]]>
        </loc>
        <lastmod>
            <?php
            $lastmod = new \DateTime('now', new \DateTimeZone(Yii::$app->timeZone));
            echo $lastmod->format('r')
            ?>
        </lastmod>
    </sitemap>
    <?php foreach ($items as $item): ?>
        <?= '<sitemap>' ?>
        <?= '<loc><![CDATA[' . $item['loc'] . ']]></loc>' ?>
        <?= '<lastmod>' . $item['lastmod'] . '</lastmod>' ?>
        <?= '</sitemap>' ?>
    <?php endforeach ?>
</sitemapindex>
