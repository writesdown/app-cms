<?php

use yii\db\Schema;

/**
 * Class m000000_000017_media_comment
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
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
            'id'                   => Schema::TYPE_PK,
            'comment_media_id'     => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'comment_author'       => Schema::TYPE_TEXT,
            'comment_author_email' => Schema::TYPE_STRING . '(100)',
            'comment_author_url'   => Schema::TYPE_STRING . '(255)',
            'comment_author_ip'    => Schema::TYPE_STRING . '(100) NOT NULL',
            'comment_date'         => Schema::TYPE_DATETIME . ' NOT NULL',
            'comment_content'      => Schema::TYPE_TEXT . ' NOT NULL',
            'comment_approved'     => Schema::TYPE_STRING . '(20) NOT NULL',
            'comment_agent'        => Schema::TYPE_STRING . '(255) NOT NULL',
            'comment_parent'       => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
            'comment_user_id'      => Schema::TYPE_INTEGER . '(11)',
            'FOREIGN KEY ([[comment_media_id]]) REFERENCES {{%media}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
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
