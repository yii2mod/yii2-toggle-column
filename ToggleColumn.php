<?php

namespace yii2mod\toggle;

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\web\View;
use Yii;

/**
 * Class ToggleColumn
 * @package yii2mod\toggle
 */
class ToggleColumn extends DataColumn
{
    /**
     * Toggle action that will be used as the toggle action in your controller
     * @var string
     */
    public $action = 'toggle';

    /**
     * Whether to use ajax or not
     * @var bool
     */
    public $enableAjax = true;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        if ($this->enableAjax) {
            $this->registerJs();
        }
    }

    /**
     * Renders the data cell content.
     * @param mixed $model the data model
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
     * @return string the rendering result
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $attribute = $this->attribute;
        $value = $model->$attribute;

        $url = [$this->action, 'id' => $model->id, 'attribute' => $attribute];

        if ($value === null || $value == true) {
            $icon = 'ok';
            $title = Yii::t('yii', 'Off');
        } else {
            $icon = 'remove';
            $title = Yii::t('yii', 'On');
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
