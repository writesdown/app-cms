<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

$uploadDir = Yii::getAlias('@public/uploads/2015/12/');

if(!is_dir($uploadDir)){
    \yii\helpers\FileHelper::createDirectory($uploadDir);
}

copy(__DIR__ . '/test-media.txt',$uploadDir . 'test-media.txt');

return [
    [
        'media_author'         => '1',
        // 'media_post_id'        => '0',
        'media_title'          => 'Test Media',
        'media_excerpt'        => 'Test Media Caption',
        'media_content'        => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p><p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.</p><p>Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.</p>',
        'media_date'           => '2015-12-06 04:56:43',
        'media_modified'       => '2015-12-06 04:56:43',
        'media_slug'           => 'test-media',
        'media_mime_type'      => 'text/plain',
        'media_comment_status' => 'open',
        'media_comment_count'  => '1',
    ]
];
