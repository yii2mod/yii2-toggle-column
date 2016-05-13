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

Once the extension is installed, simply use it in your code by  :

```php
// In your Controller
use yii2mod\toggle\actions\ToggleAction;

public function actions()
{
	return [
		'toggle' => [
			'class' => ToggleAction::className(),
			'modelClass' => 'path\to\your\Model',
			// Uncomment to enable flash messages
			//'setFlash' => true,
		]
	];
}

// In your view
use yii\grid\GridView;
use yii\widgets\Pjax;

Pjax::begin();

GridView::widget(
	[
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			'id',
			[
				'class' => '\yii2mod\toggle\ToggleColumn',
				'attribute' => 'active',
				// Uncomment if  you don't want AJAX
				// 'enableAjax' => false,
			],
			['class' => 'yii\grid\ActionColumn'],
		],
	]
);

Pjax::end();
```
