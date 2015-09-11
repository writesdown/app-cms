<?php

use yii\db\Schema;

/**
 * Class m000000_000008_taxonomy.
 * Migration class for taxonomy.
 *
 * @author Agiel K. Saputra
 */
class m000000_000008_taxonomy extends \yii\db\Migration
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

        $this->createTable('{{%taxonomy}}', [
            'id'                    => Schema::TYPE_PK,
            'taxonomy_name'         => Schema::TYPE_STRING . '(200) NOT NULL',
            'taxonomy_slug'         => Schema::TYPE_STRING . '(200) NOT NULL',
            'taxonomy_hierarchical' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'taxonomy_sn'           => Schema::TYPE_STRING . '(255) NOT NULL',
            'taxonomy_pn'           => Schema::TYPE_STRING . '(255) NOT NULL',
            'taxonomy_smb'          => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
        ], $tableOptions);

        /**
         * Add two taxonomies, that are category and tag
         */
        $this->batchInsert('{{%taxonomy}}', ['taxonomy_name', 'taxonomy_slug', 'taxonomy_hierarchical', 'taxonomy_sn', 'taxonomy_pn', 'taxonomy_smb'], [
            ['category', 'category', '1', 'Category', 'Categories', 1],
            ['tag', 'tag', '0', 'Tag', 'Tags', 0]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%taxonomy}}');
    }
}
