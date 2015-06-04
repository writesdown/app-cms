<?php

use yii\db\Expression;
use yii\db\Schema;

/**
 * Class m000000_000002_user.
 * Migration for table user.
 *
 * @author Agiel K. Saputra
 */
class m000000_000002_user extends \yii\db\Migration
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

        $this->createTable('{{%user}}', [
            'id'                   => Schema::TYPE_PK,
            'username'             => Schema::TYPE_STRING . '(255) NOT NULL',
            'email'                => Schema::TYPE_STRING . '(255) NOT NULL',
            'full_name'            => Schema::TYPE_STRING . '(255)',
            'display_name'         => Schema::TYPE_STRING . '(255)',
            'password_hash'        => Schema::TYPE_STRING . '(255) NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING . '(255)',
            'auth_key'             => Schema::TYPE_STRING . '(32) NOT NULL',
            'status'               => Schema::TYPE_SMALLINT . '(6) NOT NULL DEFAULT 5',
            'created_at'           => Schema::TYPE_DATETIME . ' NOT NULL',
            'updated_at'           => Schema::TYPE_DATETIME . ' NOT NULL',
            'login_at'             => Schema::TYPE_DATETIME,
        ], $tableOptions);

        /**
         * Insert super administrator.
         * Initialize super administrator with user superadmin and password superadmin.
         * After installing this app success, change the username and password of superadmin immediately.
         */
        $this->insert('{{%user}}', [
            'id'                   => 1,
            'username'             => 'superadmin',
            'email'                => 'superadministrator@writesdown.com',
            'full_name'            => 'Super Administrator',
            'display_name'         => 'Super Admin',
            'password_hash'        => '$2y$13$WJIxqq6WBZUw7tyfN2oiH.WJtPntvLMjs6NG9uW0M3Lh71lImaEyu',
            'password_reset_token' => null,
            'auth_key'             => '7QvEmdZDvaSxM1-oYoWkKso0ws6AHTX1',
            'status'               => 10,
            'created_at'           => new Expression('NOW()'),
            'updated_at'           => new Expression('NOW()'),
            'login_at'             => new Expression('NOW()'),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
