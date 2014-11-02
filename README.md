yii2-format-converter
=====================
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require mdmsoft//yii2-format-converter "dev-master"
```

or add

```
"mdmsoft//yii2-format-converter": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your ActiveRecord class:

# DateConverter Behavior
Use to convert date format from database format to logical format

```php
public function behaviors()
{
    return [
        [
            'class' => 'mdm\converter\DateConverter',
            'type' => 'date', // 'date', 'time', 'datetime'
            'logicalFormat' => 'php:d/m/Y', // default to locale format
            'physicalFormat' => 'php:Y-m-d', // database level format, default to 'Y-m-d'
            'attributes' => [
                'Date' => 'date', // date is original attribute
            ]
        ],
        ...
    ]
}
```

then add attribute `Date` to your model rules.

```php
// in view view.php
echo DetailView::widget([
	'options' => ['class' => 'table table-striped detail-view', 'style' => 'padding:0px;'],
	'model' => $model,
	'attributes' => [
		'sales_num',
		'supplier.name',
		'Date', // use attribute 'Date' instead of 'sales_date'
		'nmStatus',
	],
]);


// in view _form.php 
echo $form->field($model, 'Date')
	->widget('yii\jui\DatePicker', [
		'options' => ['class' => 'form-control', 'style' => 'width:50%'],
		'dateFormat' => 'php:d/m/Y', 
]);
```

