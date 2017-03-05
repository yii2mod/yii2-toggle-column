<?php

namespace yii2mod\toggle\tests\data;

use yii\db\ActiveRecord;

/**
 * Class Post
 *
 * @property string $title
 * @property int $status
 */
class Post extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status'], 'integer'],
        ];
    }
}
