<?php

use yii\db\Schema;

/**
 * Class m000000_000005_auth_item_child.
 * Migration class for auth_item_child.
 *
 * @author Agiel K. Saputra
 */
class m000000_000005_auth_item_child extends \yii\db\Migration
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

        $this->createTable('{{%auth_item_child}}', [
            'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
            'child'  => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY ([[parent]], [[child]])',
            'FOREIGN KEY ([[child]]) REFERENCES {{%auth_item}} ([[name]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * Level superadmin => adminstrator => editor => author => contributor => subscriber.
         */
        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['superadmin', 'administrator'],
            ['administrator', 'editor'],
            ['editor', 'author'],
            ['author', 'contributor'],
            ['contributor', 'subscriber'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%auth_item_child}}');
    }
}
