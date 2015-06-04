<?php

use yii\db\Schema;

/**
 * Class m000000_000012_term_relationship
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 */
class m000000_000012_term_relationship extends \yii\db\Migration
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
        
        $this->createTable('{{%term_relationship}}', [
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'term_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'PRIMARY KEY ([[post_id]], [[term_id]])',
            'FOREIGN KEY ([[post_id]]) REFERENCES {{%post}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[term_id]]) REFERENCES {{%term}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * First post has post-category and post-tag
         */
        $this->batchInsert('{{%term_relationship}}', ['post_id', 'term_id'], [
            [1, 1],
            [1, 2]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%term_relationship}}');
    }
}
