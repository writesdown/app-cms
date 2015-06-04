<?php

use yii\db\Schema;

/**
 * Class m000000_093700_term
 *
 * @author Agiel K. Saputra
 */
class m000000_000010_term extends \yii\db\Migration
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
        
        $this->createTable('{{%term}}', [
            'id' => Schema::TYPE_PK,
            'taxonomy_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'term_name' => Schema::TYPE_STRING . '(200) NOT NULL',
            'term_slug' => Schema::TYPE_STRING . '(200) NOT NULL',
            'term_description' => Schema::TYPE_TEXT,
            'term_parent' => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
            'term_count' => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
            'FOREIGN KEY ([[taxonomy_id]]) REFERENCES {{%taxonomy}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->batchInsert('{{%term}}', ['taxonomy_id', 'term_name', 'term_slug', 'term_description', 'term_parent', 'term_count'], [
            ['1', 'Sample Category', 'sample-category', 'Hello there, this is example of category.', 0, '1'],
            ['2', 'Sample Tag', 'sample-tag', 'Hello there, this is an example of tag.', 0, '1'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%term}}');
    }
}
