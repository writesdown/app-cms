<?php

use yii\db\Schema;

/**
 * Class m000000_000015_media
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class m000000_000015_media extends \yii\db\Migration
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

        $this->createTable('{{%media}}', [
            'id' => Schema::TYPE_PK,
            'author' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'post_id' => Schema::TYPE_INTEGER . '(11)',
            'title' => Schema::TYPE_TEXT . ' NOT NULL',
            'excerpt' => Schema::TYPE_TEXT,
            'content' => Schema::TYPE_TEXT,
            'password' => Schema::TYPE_STRING . '(255)',
            'date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'modified' => Schema::TYPE_DATETIME . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(255) NOT NULL',
            'mime_type' => Schema::TYPE_STRING . '(100) NOT NULL',
            'comment_status' => Schema::TYPE_STRING . "(20) NOT NULL DEFAULT 'open'",
            'comment_count' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0',
            'FOREIGN KEY ([[post_id]]) REFERENCES {{%post}} ([[id]]) ON DELETE SET NULL ON UPDATE CASCADE',
            'FOREIGN KEY ([[author]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%media}}');
    }
}
