<?php
/**
 * @file      post-type.php.
 * @date      6/4/2015
 * @time      11:26 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?= '<?xml-stylesheet type="text/xsl" href="' . Yii::$app->urlManager->createAbsoluteUrl(['sitemap/style']) . '"?>'; ?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($items as $item) { ?>
        <url>
            <loc><![CDATA[<?= $item['loc']; ?>]]></loc>
            <lastmod><?= $item['lastmod']; ?></lastmod>
            <changefreq><?= $item['changefreq']; ?></changefreq>
            <priority><?= $item['priority']; ?></priority>
            <?php
            if (isset($item['image'])) {
                foreach ($item['image'] as $image) {
                    ?>
                    <?= '<image:image>'; ?>
                    <?= '<image:loc><![CDATA[' . $image['loc'] . ']]></image:loc>'; ?>
                    <?= isset($image['title']) ? '<image:title><![CDATA[' . $image['title'] . ']]></image:title>' : ''; ?>
                    <?= isset($image['caption']) ? ' <image:caption><![CDATA[' . $image['caption'] . ']]></image:caption>' : ''; ?>
                    <?= '</image:image>' ?>
                <?php
                }
            }
            ?>
        </url>
    <?php } ?>
</urlset>