<?php

use yii\db\Schema;

/**
 * Class m000000_000006_auth_assignment.
 * Migration class for auth_assignment.
 *
 * @author Agiel K. Saputra
 */
class m000000_000006_auth_assignment extends \yii\db\Migration
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

        $this->createTable('{{%auth_assignment}}', [
            'item_name'  => Schema::TYPE_STRING . '(64) NOT NULL',
            'user_id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'PRIMARY KEY ([[item_name]], [[user_id]])',
            'FOREIGN KEY ([[user_id]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[item_name]]) REFERENCES {{%auth_item}} ([[name]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * Assign first user as super administrator
         */
        $this->insert('{{%auth_assignment}}', [
            'item_name'  => 'superadmin',
            'user_id'    => 1,
            'created_at' => null
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%auth_assignment}}');
    }
}
