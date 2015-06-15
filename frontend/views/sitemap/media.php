<?php
/**
 * @file      media.php.
 * @date      6/4/2015
 * @time      11:26 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<?= '<?xml-stylesheet type="text/xsl" href="' . Yii::$app->urlManager->createAbsoluteUrl(['sitemap/style']) . '"?>'; ?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($items as $item) { ?>
        <url>
            <loc><![CDATA[<?= $item['loc']; ?>]]></loc>
            <lastmod><?= $item['lastmod']; ?></lastmod>
            <changefreq><?= $item['changefreq']; ?></changefreq>
            <priority><?= $item['priority']; ?></priority>
        </url>
    <?php } ?>
</urlset>
