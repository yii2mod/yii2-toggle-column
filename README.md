Toggle data column for Yii2
===========================
Provides a toggle data column and action for Yii Framework 2.0

[![Latest Stable Version](https://poser.pugx.org/yii2mod/yii2-toggle-column/v/stable)](https://packagist.org/packages/yii2mod/yii2-toggle-column) [![Total Downloads](https://poser.pugx.org/yii2mod/yii2-toggle-column/downloads)](https://packagist.org/packages/yii2mod/yii2-toggle-column) [![License](https://poser.pugx.org/yii2mod/yii2-toggle-column/license)](https://packagist.org/packages/yii2mod/yii2-toggle-column)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2mod/yii2-toggle-column "*"
```

or add

```
"yii2mod/yii2-toggle-column": "*"
```

to the require section of your `composer.json` file.


Usage
-----

1) In your GridView columns section:

```php
[
    'class' => '\yii2mod\toggle\ToggleColumn',
    'attribute' => 'active',
],
```

2) Add `toggle action` to your controller as follows:

```php
public function actions()
{
   return [
        'toggle' => [
            'class' => \yii2mod\toggle\actions\ToggleAction::class,
            'modelClass' => 'path\to\your\Model',
        ],
    ];
}
```
