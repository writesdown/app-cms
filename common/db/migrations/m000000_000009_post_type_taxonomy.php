<?php

use yii\db\Schema;

/**
 * Class m000000_000009_post_type_taxonomy
 *
 * @author Agiel K. Saputra
 */
class m000000_000009_post_type_taxonomy extends \yii\db\Migration
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

        $this->createTable('{{%post_type_taxonomy}}', [
            'post_type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'taxonomy_id'  => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'PRIMARY KEY ([[post_type_id]], [[taxonomy_id]])',
            'FOREIGN KEY ([[post_type_id]]) REFERENCES {{%post_type}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[taxonomy_id]]) REFERENCES {{%taxonomy}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * The post type of "post" has category and tag
         */
        $this->batchInsert('{{%post_type_taxonomy}}', ['post_type_id', 'taxonomy_id'], [
            [1, 1],
            [1, 2]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%post_type_taxonomy}}');
    }
}
