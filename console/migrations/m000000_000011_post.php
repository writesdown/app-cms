<?php

use yii\db\Expression;
use yii\db\Schema;

/**
 * Class m000000_000011_post
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class m000000_000011_post extends \yii\db\Migration
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

        $this->createTable('{{%post}}', [
            'id' => Schema::TYPE_PK,
            'author' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'type' => Schema::TYPE_INTEGER . '(11)',
            'title' => Schema::TYPE_TEXT . ' NOT NULL',
            'excerpt' => Schema::TYPE_TEXT,
            'content' => Schema::TYPE_TEXT,
            'date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'modified' => Schema::TYPE_DATETIME . ' NOT NULL',
            'status' => Schema::TYPE_STRING . "(20) NOT NULL DEFAULT 'publish'",
            'password' => Schema::TYPE_STRING . '(255)',
            'slug' => Schema::TYPE_STRING . '(255) NOT NULL',
            'comment_status' => Schema::TYPE_STRING . "(20) NOT NULL DEFAULT 'open'",
            'comment_count' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0',
            'FOREIGN KEY ([[type]]) REFERENCES {{%post_type}} ([[id]]) ON DELETE SET NULL ON UPDATE CASCADE',
            'FOREIGN KEY ([[author]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        /**
         * Insert first post and first page
         */
        $this->batchInsert('{{%post}}', [
            'id',
            'author',
            'type',
            'title',
            'excerpt',
            'content',
            'date',
            'modified',
            'status',
            'password',
            'slug',
            'comment_status',
            'comment_count',
        ], [
            [
                1,
                1,
                1,
                "Sample Post",
                "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.",
                "<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p><p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.</p><p>Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.</p>",
                new Expression('NOW()'),
                new Expression('NOW()'),
                "publish",
                null,
                "sample-post",
                "open",
                1,
            ],
            [
                2,
                1,
                2,
                "Sample Page",
                "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.",
                "<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p><blockquote><p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.</p></blockquote><p>Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.</p>",
                new Expression('NOW()'),
                new Expression('NOW()'),
                "publish",
                null,
                "sample-page",
                "close",
                0,
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%post}}');
    }
}
