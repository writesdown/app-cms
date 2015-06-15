<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      11:26 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<?= '<?xml-stylesheet type="text/xsl" href="' . Yii::$app->urlManager->createAbsoluteUrl(['sitemap/style']) . '"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc><![CDATA[<?= Yii::$app->urlManager->createAbsoluteUrl(['sitemap/view', 'type' => 'h', 'slug' => 'home', 'page' => 1]); ?>]]></loc>
        <lastmod>
            <?php
            $lastmod = new \DateTime('now', new \DateTimeZone(Yii::$app->timeZone));
            echo $lastmod->format('r')
            ?>
        </lastmod>
    </sitemap>
    <?php foreach ($items as $item) {
        echo '<sitemap>';
        echo '<loc><![CDATA[' . $item['loc'] . ']]></loc>';
        echo '<lastmod>' . $item['lastmod'] . '</lastmod>';
        echo '</sitemap>';
    }; ?>
</sitemapindex>
