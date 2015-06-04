<?php

use yii\db\Schema;

/**
 * Class m000000_000004_auth_item.
 * Migration for table auth item.
 *
 * @author Agiel K. Saputra
 */
class m000000_000004_auth_item extends \yii\db\Migration
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

        $this->createTable('{{%auth_item}}', [
            'name'        => Schema::TYPE_STRING . '(64) NOT NULL',
            'type'        => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name'   => Schema::TYPE_STRING . '(64)',
            'data'        => Schema::TYPE_TEXT,
            'created_at'  => Schema::TYPE_INTEGER . '(11)',
            'updated_at'  => Schema::TYPE_INTEGER . '(11)',
            'PRIMARY KEY ([[name]])',
            'FOREIGN KEY ([[rule_name]]) REFERENCES {{%auth_rule}} ([[name]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * Default roles of this application are superadmin, administrator, editor, author, contributor, subscriber.
         */
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'], [
            ['superadmin', 1, 'Super Administrator', null, null, 0, 0],
            ['administrator', 1, 'Administrator', null, null, 0, 0],
            ['editor', 1, 'Editor', null, null, 0, 0],
            ['author', 1, 'Author', null, null, 0, 0],
            ['contributor', 1, 'Contributor', null, null, 0, 0],
            ['subscriber', 1, 'Subscriber', null, null, 0, 0]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%auth_item}}');
    }
}
