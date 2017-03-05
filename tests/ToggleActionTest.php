<?php

namespace yii2mod\toggle\tests;

use yii\web\Response;
use yii2mod\toggle\actions\ToggleAction;
use yii2mod\toggle\tests\data\Post;

/**
 * Class ToggleActionTest
 *
 * @package yii2mod\toggle\tests
 */
class ToggleActionTest extends TestCase
{
    /**
     * @var Post
     */
    private $_model;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_model = Post::find()->one();
    }

    /**
     * Runs the action.
     *
     * @param $id
     * @param $attribute
     * @param array $config
     *
     * @return Response
     */
    protected function runAction($id, $attribute, $config = [])
    {
        $config['modelClass'] = Post::class;

        $action = $action = new ToggleAction('toggle', $this->createController(), $config);

        return $action->run($id, $attribute);
    }

    // Tests :

    public function testToggleAction()
    {
        $response = $this->runAction($this->_model->id, 'status');

        $this->assertEquals(302, $response->statusCode);
        $this->_model->refresh();
        $this->assertEquals(1, $this->_model->status);

        $this->runAction($this->_model->id, 'status');
        $this->_model->refresh();
        $this->assertEquals(0, $this->_model->status);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWrongAttribute()
    {
        $this->runAction($this->_model->id, 'wrong-attribute');
    }

    public function testPreProcess()
    {
        $this->runAction($this->_model->id, 'status', [
            'preProcess' => function ($model) {
                $model->title = 'changed title';
                $model->save();
            },
        ]);

        $this->_model->refresh();
        $this->assertEquals('changed title', $this->_model->title);
    }

    /**
     * @expectedException \yii\web\BadRequestHttpException
     */
    public function testWrongModel()
    {
        $this->runAction(2, 'status');
    }
}
