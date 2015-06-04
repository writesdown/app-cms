<?php

use yii\db\Schema;

/**
 * Class m000000_000003_auth_rule.
 * Migration for table auth rule.
 *
 * @author Agiel K. Saputra
 */
class m000000_000003_auth_rule extends \yii\db\Migration
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

        $this->createTable('{{%auth_rule}}', [
            'name'       => Schema::TYPE_STRING . '(64) NOT NULL',
            'data'       => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'PRIMARY KEY ([[name]])',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%auth_rule}}');
    }
}
