<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

$uploadDir = Yii::getAlias('@public/uploads/2015/12/');

if (!is_dir($uploadDir)) {
    \yii\helpers\FileHelper::createDirectory($uploadDir);
}

copy(__DIR__ . '/test-media.txt', $uploadDir . 'test-media.txt');

return [
    [
        'author' => '1',
        //'media_post_id'  => '1',
        'title' => 'Test Media',
        'excerpt' => 'Test Media Caption',
        'content' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p><p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.</p><p>Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.</p>',
        'date' => '2015-12-06 04:56:43',
        'modified' => '2015-12-06 04:56:43',
        'slug' => 'test-media',
        'mime_type' => 'text/plain',
        'comment_status' => 'open',
        'comment_count' => '1',
    ],
];
