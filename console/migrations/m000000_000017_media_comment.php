<?php

use yii\db\Schema;

/**
 * Class m000000_000017_media_comment
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class m000000_000017_media_comment extends \yii\db\Migration
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

        $this->createTable('{{%media_comment}}', [
            'id' => Schema::TYPE_PK,
            'media_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'author' => Schema::TYPE_TEXT,
            'email' => Schema::TYPE_STRING . '(100)',
            'url' => Schema::TYPE_STRING . '(255)',
            'ip' => Schema::TYPE_STRING . '(100) NOT NULL',
            'date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'status' => Schema::TYPE_STRING . '(20) NOT NULL',
            'agent' => Schema::TYPE_STRING . '(255) NOT NULL',
            'parent' => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'FOREIGN KEY ([[media_id]]) REFERENCES {{%media}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%media_comment}}');
    }
}
