<?php

use yii\db\Schema;

/**
 * Class m000000_000015_media
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
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
            'media_author' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'media_post_id' => Schema::TYPE_INTEGER . '(11)',
            'media_title' => Schema::TYPE_TEXT . ' NOT NULL',
            'media_excerpt' => Schema::TYPE_TEXT,
            'media_content' => Schema::TYPE_TEXT,
            'media_password' => Schema::TYPE_STRING . '(255)',
            'media_date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'media_modified' => Schema::TYPE_DATETIME . ' NOT NULL',
            'media_slug' => Schema::TYPE_STRING . '(255) NOT NULL',
            'media_mime_type' => Schema::TYPE_STRING . '(100) NOT NULL',
            'media_comment_status' => Schema::TYPE_STRING . "(20) NOT NULL DEFAULT 'open'",
            'media_comment_count' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0',
            'FOREIGN KEY ([[media_post_id]]) REFERENCES {{%post}} ([[id]]) ON DELETE SET NULL ON UPDATE CASCADE',
            'FOREIGN KEY ([[media_author]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
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
