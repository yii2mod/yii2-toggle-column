<?php

namespace yii2mod\toggle;

use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\web\View;

/**
 * Class ToggleColumn
 *
 * @package yii2mod\toggle
 */
class ToggleColumn extends DataColumn
{
    /**
     * Toggle action that will be used as the toggle action in your controller
     *
     * @var string
     */
    public $action = 'toggle';

    /**
     * Whether to use ajax or not
     *
     * @var bool
     */
    public $enableAjax = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->enableAjax) {
            $this->registerJs();
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $attribute = $this->attribute;
        $value = $model->$attribute;

        $url = [$this->action, 'id' => $model->id, 'attribute' => $attribute];

        if ($value === null || $value == true) {
            $icon = 'ok';
            $title = Yii::t('app', 'Off');
        } else {
            $icon = 'remove';
            $title = Yii::t('app', 'On');
        }

        return Html::a(
            '<span class="glyphicon glyphicon-' . $icon . '"></span>',
            $url,
            [
                'title' => $title,
                'class' => 'toggle-column',
                'data-method' => 'post',
                'data-pjax' => '0',
            ]
        );
    }

    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        $js = <<< JS
            $("a.toggle-column").on("click", function(e) {
                e.preventDefault();
                $.post($(this).attr("href"), function(data) {
                  var pjaxId = $(e.target).closest(".grid-view").parent().attr("id");
                  $.pjax.reload({container:"#" + pjaxId, timeout: 5000});
                });
                return false;
            });
JS;
        $this->grid->view->registerJs($js, View::POS_READY, 'yii2mod-toggle-column');
    }
}
