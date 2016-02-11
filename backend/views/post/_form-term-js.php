<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use dosamigos\selectize\SelectizeAsset;
use yii\helpers\Url;

/* @var $model common\models\Post */

SelectizeAsset::register($this);
$onItemRemove = null;
$onItemAdd = null;
if (!$model->isNewRecord) {
    $onItemRemove = 'onItemRemove: function (value) {
        $.ajax({
            url: "' . Url::to(['/term-relationship/ajax-delete-non-hierarchical']) . '",
            data: {
                TermRelationship: {
                    post_id: "' . $model->id . '",
                    term_id: value
                },
                _csrf: yii.getCsrfToken()
            },
            type: "POST"
        });
    },';
    $onItemAdd = 'onItemAdd: function (value) {
        $.ajax({
            url: "' . Url::to(['/term-relationship/ajax-create-non-hierarchical']) . '",
            data: {
                TermRelationship: {
                    post_id: "' . $model->id . '",
                    term_id: value
                },
                _csrf: yii.getCsrfToken()
            },
            type: "POST"
        });
    },';
}
$this->registerJs('(function($){
    $(".term-relationship.taxonomy-not-hierarchical").each(function(){
        var _this = $(this);
        $(this).selectize({
            valueField: "id",
            labelField: "name",
            searchField: "name",
            delimiter: ",",
            plugins: ["remove_button"],
            create: function (input, callback) {
                $.ajax({
                    url: "' . Url::to(['/term/ajax-create-non-hierarchical']) . '",
                    data: {
                        Term: {
                            name: input,
                            taxonomy_id: _this.data("taxonomy_id")
                        },
                        _csrf: yii.getCsrfToken() },
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                       callback(response);
                    }
                });
            },'
    . $onItemAdd
    . $onItemRemove
    . 'load: function (query, callback) {
            if (!query.length) return callback();
                $.ajax({
                    url: "' . Url::to(['/term/ajax-search']) . '",
                    type: "POST",
                    dataType: "json",
                    data: {
                        Term: {
                            name: query,
                            taxonomy_id: _this.data("taxonomy_id")
                        },
                        _csrf: yii.getCsrfToken()
                    },
                    error: function() {
                        callback();
                    },
                    success: function(response) {
                        callback(response);
                    }
                });
            }
        });
    });
}(jQuery));');
