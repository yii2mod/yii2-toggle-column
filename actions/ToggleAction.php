<?php

namespace yii2mod\toggle\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

/**
 * Class ToggleAction
 * @package yii2mod\toggle\actions
 */
class ToggleAction extends Action
{
    /**
     * @var string name of the model
     */
    public $modelClass;

    /**
     * @var string|int|boolean what to set active models to
     */
    public $onValue = 1;

    /**
     * @var string|int|boolean what to set inactive models to
     */
    public $offValue = 0;

    /**
     * @var bool whether to set flash messages or not
     */
    public $setFlash = false;

    /**
     * @var string flash message on success
     */
    public $flashSuccess = "Model saved";

    /**
     * @var string flash message on error
     */
    public $flashError = "Error saving Model";

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
     * Run the action
     * @param $id integer id of model to be loaded
     *
     * @param $attribute
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws \yii\base\ExitException
     * @return mixed
     */
    public function run($id, $attribute)
    {
        $id = (int)$id;

        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }

        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        $model = $this->findModel($modelClass, $id);

        if (!$model->hasAttribute($attribute)) {
            throw new InvalidConfigException("Attribute doesn't exist.");
        }

        if ($model->$attribute == $this->onValue) {
            $model->$attribute = $this->offValue;
        } else {
            $model->$attribute = $this->onValue;
        }
        // do we have a preProcess function
        if ($this->preProcess && is_callable($this->preProcess, true)) {
            call_user_func($this->preProcess, $model);
        }
        
        if ($model->save()) {
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
        /* @var $controller \yii\web\Controller */
        $controller = $this->controller;
        if (!empty($this->redirect)) {
            return $controller->redirect($this->redirect);
        }
        return $controller->redirect(Yii::$app->request->getReferrer());
    }

    /**
     * Find Model
     * @param $modelClass
     * @param $id
     * @throws NotFoundHttpException
     * @internal param $model
     * @author Igor Chepurnoy
     */
    public function findModel($modelClass, $id) {
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Record does not exist.');
        }
    }
}
