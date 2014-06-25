yii2-format-converter
=====================
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require mdmsoft//yii2-format-converter "*"
```

or add

```
"mdmsoft//yii2-format-converter": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your ActiveRecord class:

```php
public function behaviors()
{
    return [
        [
            'class' => 'mdm\converter\DateConverter',
            'logicalFormat' => 'd/m/Y', // your readeble datetime format, default to 'd-m-Y'
            'logicalFormat' => 'Y-m-d', // database level format, default to 'Y-m-d'
            'attributes' => [
                'salesDate' => 'sales_date', // sales_date is original attribute
            ]
        ],
        ...
    ]
}
```
then add attribute `salesDate` to your model rules.

```php
// in view view.php
echo DetailView::widget([
	'options' => ['class' => 'table table-striped detail-view', 'style' => 'padding:0px;'],
	'model' => $model,
	'attributes' => [
		'sales_num',
		'idSupplier.nm_supplier',
		'salesDate', // use attribute 'salesDate' instead of 'sales_date'
		'nmStatus',
	],
]);


// in view _form.php 
echo $form->field($model, 'salesDate') // use attribute 'salesDate' instead of 'sales_date'
	->widget('yii\jui\DatePicker', [
		'options' => ['class' => 'form-control', 'style' => 'width:50%'],
		'clientOptions' => [
			'dateFormat' => 'dd/mm/yy', 
		],
]);

```
