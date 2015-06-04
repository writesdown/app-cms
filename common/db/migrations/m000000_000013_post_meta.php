<?php

use yii\db\Schema;

/**
 * Class m000000_000013_post_meta
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 */
class m000000_000013_post_meta extends \yii\db\Migration
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
        
        $this->createTable('{{%post_meta}}', [
            'id' => Schema::TYPE_PK,
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'meta_name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'meta_value' => Schema::TYPE_TEXT . ' NOT NULL',
            'FOREIGN KEY ([[post_id]]) REFERENCES {{%post}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%post_meta}}');
    }
}
