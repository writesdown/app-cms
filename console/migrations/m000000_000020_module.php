<?php

use yii\db\Schema;

/**
 * Class m000000_000020_module
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class m000000_000020_module extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%module}}', [
            'id'                 => Schema::TYPE_PK,
            'module_name'        => Schema::TYPE_STRING . '(64) NOT NULL',
            'module_title'       => Schema::TYPE_TEXT . ' NOT NULL',
            'module_description' => Schema::TYPE_TEXT,
            'module_config'      => Schema::TYPE_TEXT . ' NOT NULL',
            'module_status'      => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'module_dir'         => Schema::TYPE_STRING . '(128) NOT NULL',
            'module_bb'          => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'module_fb'          => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'module_date'        => Schema::TYPE_DATETIME . ' NOT NULL',
            'module_modified'    => Schema::TYPE_DATETIME . ' NOT NULL',
        ], $tableOptions);

        /**
         * Insert data module
         */
        $this->batchInsert('{{%module}}', [
            'module_name',
            'module_title',
            'module_description',
            'module_config',
            'module_status',
            'module_dir',
            'module_bb',
            'module_fb',
            'module_date',
            'module_modified',
        ], [
            [
                'toolbar',
                'Toolbar',
                null,
                '{"frontend":{"class":"modules\\\\toolbar\\\\frontend\\\\Module"}}',
                0,
                'toolbar',
                0,
                1,
                '2015-09-11 03:14:57',
                '2015-09-11 03:14:57',
            ],
            [
                'sitemap',
                'Site Map',
                'Module for sitemap',
                '{"backend":{"class":"modules\\\\sitemap\\\\backend\\\\Module"},"frontend":{"class":"modules\\\\sitemap\\\\frontend\\\\Module"}}',
                0,
                'sitemap',
                0,
                1,
                '2015-09-11 03:38:25',
                '2015-09-11 03:38:25',
            ],
            [
                'feed',
                'RSS Feed',
                null,
                '{"frontend":{"class":"modules\\\\feed\\\\frontend\\\\Module"}}',
                0,
                'feed',
                0,
                0,
                '2015-09-11 03:38:53',
                '2015-09-11 03:38:53',
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%module}}');
    }
}
