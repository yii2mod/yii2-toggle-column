<?php

namespace yii2mod\toggle\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;

/**
 * Class ToggleAction
 *
 * @package yii2mod\toggle\actions
 */
class ToggleAction extends Action
{
    /**
     * @var string name of the model
     */
    public $modelClass;

    /**
     * @var string|int|bool what to set active models to
     */
    public $onValue = 1;

    /**
     * @var string|int|bool what to set inactive models to
     */
    public $offValue = 0;

    /**
     * @var bool whether to set flash messages or not
     */
    public $setFlash = false;

    /**
     * @var string flash message on success
     */
    public $flashSuccess = 'Model saved';

    /**
     * @var string flash message on error
     */
    public $flashError = 'Error saving Model';

    /**
     * @var string|array URL to redirect to
     */
    public $redirect;

    /**
     * @var \Closure a function to be called previous saving model. The anonymous function is preferable to have the
     * model passed by reference. This is useful when we need to set model with extra data previous update.
     */
    public $preProcess;

    /**
     * @var string default pk column name
     */
    public $pkColumn = 'id';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    /**
     * Change column value
     *
     * @param $id
     * @param $attribute
     *
     * @return \yii\web\Response
     *
     * @throws InvalidConfigException
     */
    public function run($id, $attribute)
    {
        $model = $this->findModel($this->modelClass, $id);

        if (!$model->hasAttribute($attribute)) {
            throw new InvalidConfigException("Attribute doesn't exist.");
        }

        if ($model->$attribute == $this->onValue) {
            $model->$attribute = $this->offValue;
        } else {
            $model->$attribute = $this->onValue;
        }

        if ($this->preProcess && is_callable($this->preProcess, true)) {
            call_user_func($this->preProcess, $model);
        }

        if ($model->save(true, [$attribute])) {
            if ($this->setFlash) {
                Yii::$app->session->setFlash('success', $this->flashSuccess);
            }
        } else {
            if ($this->setFlash) {
                Yii::$app->session->setFlash('error', $this->flashError);
            }
        }

        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->end();
        }

        if (!empty($this->redirect)) {
            return $this->controller->redirect($this->redirect);
        }

        return $this->controller->redirect(Yii::$app->request->getReferrer());
    }

    /**
     * Find Model
     *
     * @param $modelClass
     * @param $id
     *
     * @return ActiveRecord
     *
     * @throws BadRequestHttpException
     */
    public function findModel($modelClass, $id)
    {
        if (($model = $modelClass::findOne([$this->pkColumn => $id])) !== null) {
            return $model;
        } else {
            throw new BadRequestHttpException('Entity not found by primary key ' . $id);
        }
    }
}
