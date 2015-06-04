<?php

use yii\db\Schema;

/**
 * Class m000000_000007_post_type.
 * Migration class for post_type.
 *
 * @author Agiel K. Saputra
 */
class m000000_000007_post_type extends \yii\db\Migration
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

        $this->createTable('{{%post_type}}', [
            'id'                    => Schema::TYPE_PK,
            'post_type_name'        => Schema::TYPE_STRING . '(64) NOT NULL',
            'post_type_slug'        => Schema::TYPE_STRING . '(64) NOT NULL',
            'post_type_description' => Schema::TYPE_TEXT,
            'post_type_icon'        => Schema::TYPE_STRING . '(255)',
            'post_type_sn'          => Schema::TYPE_STRING . '(255) NOT NULL',
            'post_type_pn'          => Schema::TYPE_STRING . '(255) NOT NULL',
            'post_type_smb'         => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'post_type_permission'  => Schema::TYPE_STRING . '(64) NOT NULL',
        ], $tableOptions);

        /**
         * Initialize post type with "post" and "page"
         */
        $this->batchInsert('{{%post_type}}', ['id', 'post_type_name', 'post_type_slug', 'post_type_icon', 'post_type_sn', 'post_type_pn', 'post_type_smb', 'post_type_permission'], [
            ['1', 'post', 'post', 'fa fa-thumb-tack', 'Post', 'Posts', 0, 'contributor'],
            ['2', 'page', 'page', 'fa fa-file-o', 'Page', 'Pages', 1, 'editor']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%post_type}}');
    }
}
