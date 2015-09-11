<?php

use yii\db\Schema;
use yii\db\Expression;

/**
 * Class m000000_000014_post_comment
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 */
class m000000_000014_post_comment extends \yii\db\Migration
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

        $this->createTable('{{%post_comment}}', [
            'id'                   => Schema::TYPE_PK,
            'comment_post_id'      => Schema::TYPE_INTEGER . '(11) NOT NULL',
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
            'FOREIGN KEY ([[comment_post_id]]) REFERENCES {{%post}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * Insert first comment for first post
         */
        $this->insert('{{%post_comment}}', [
            'comment_post_id'      => 1,
            'comment_author'       => 'WD, WritesDown',
            'comment_author_email' => 'wd@writesdown.com',
            'comment_author_url'   => 'http://www.writesdown.com/',
            'comment_author_ip'    => '',
            'comment_date'         => new Expression('NOW()'),
            'comment_content'      => 'SAMPLE COMMENT: Nullam accumsan lorem in dui. Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices mauris.',
            'comment_approved'     => 'approved',
            'comment_agent'        => '',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%post_comment}}');
    }
}
