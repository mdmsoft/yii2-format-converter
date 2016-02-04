yii2-format-converter
=====================
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require mdmsoft/yii2-format-converter "~1.0"
```

or add

```
"mdmsoft/yii2-format-converter": "~1.0"
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

# RelatedConverter Behavior

Convert `id` to `name` of related model

```php
// attach to model
public function behaviors()
{
    return [
        [
            'class' => 'mdm\converter\RelatedConverter',
            'attributes => [
                'supplierName' => ['supplier', 'name'], // use avaliable relation
                'branchName' => [[Branch::className(), 'id' => 'branch_id'], 'name'], // use classname
            ]
        ],
    ];
}

// usage
$model->supplierName = 'Donquixote Family';
$model->branchName = 'North Blue';

// in form
<?= $form->field($model,'supplierName'); ?>

```

# EnumConverter Behavior

Use to convert constant value to constant name.

```php
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_DELETED = 3;

    ...

    public function behaviors()
    {
        return [
            [
                'class' => 'mdm\converter\EnumConverter',
                'attributes => [
                    'statusName' => 'status', // 
                ],
                'prefix' => 'STATUS_'
            ],
        ];
    }
}

// usage
$model->status = Post::STATUS_PUBLISHED;

echo $model->statusName; // return Published
```

# EnumTrait

Use to get list of constant

```php
class Post extends ActiveRecord
{
    use \mdm\converter\EnumTrait;
    
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_DELETED = 3;

    public function getNmStatus()
    {
        return $this->getLogical('status', 'STATUS_');
    }
    
    public function setNmStatus($value)
    {
        return $this->setLogical('status', 'STATUS_', $value);
    }
    
}

// usage
$model->nmStatus = 'DRAFT'; // eq $model->status = 1;

$model->status = 2;
echo $model->nmStatus; // return PUBLISHED;

Post::enums('STATUS_');
/*
[
    1 => 'DRAFT',
    2 => 'PUBLISHED',
    3 => 'DELETED',
]
*/ 

Post::constants('STATUS_');
/*
[
    'DRAFT' => 1,
    'PUBLISHED' => 2,
    'DELETED' => 3,
]
*/ 

```