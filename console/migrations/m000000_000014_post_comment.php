<?php

use yii\db\Expression;
use yii\db\Schema;

/**
 * Class m000000_000014_post_comment
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
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
            'id' => Schema::TYPE_PK,
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
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
            'FOREIGN KEY ([[post_id]]) REFERENCES {{%post}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * Insert first comment for first post
         */
        $this->insert('{{%post_comment}}', [
            'post_id' => 1,
            'author' => 'WD, WritesDown',
            'email' => 'wd@writesdown.com',
            'url' => 'http://www.writesdown.com/',
            'ip' => '',
            'date' => new Expression('NOW()'),
            'content' => 'SAMPLE COMMENT: Nullam accumsan lorem in dui. Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices mauris.',
            'status' => 'approved',
            'agent' => '',
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
