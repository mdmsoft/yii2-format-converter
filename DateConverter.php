<?php

namespace mdm\converter;

use Yii;
use yii\helpers\FormatConverter;

/**
 * Description of DateConverter
 *
 * ~~~
 * // attach as behavior
 * [
 *     'class' => 'mdm\converter\DateConverter',
 *     'logicalFormat' => 'php:d/m/Y',
 *     'attributes => [
 *         'createdDate' => 'created_date',
 *         'deliveryDate' => 'delivery_date',
 *     ]
 * ]
 * 
 * // then attribute directly
 * $model->createdDate = '24/10/2014'; // equivalent with $model->created_date = '2014-10-24'
 * ~~~
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DateConverter extends BaseConverter
{
    /**
     * @var string 'date', 'time', or 'datetime'.
     */
    public $type = 'date';

    /**
     * @var string Logical format. 
     */
    public $logicalFormat;

    /**
     * @var string Normalize logical format. 
     */
    private $_phpLogicalFormat;

    /**
     * @var string Logical format. 
     */
    public $physicalFormat;

    /**
     * @var string Normalize logical format. 
     */
    private $_phpPhysicalFormat;

    /**
     * @var array default format 
     */
    public static $dbDatetimeFormat = [
        'mysql' => [
            'date' => 'yyyy-MM-dd',
            'time' => 'HH:mm:ss',
            'datetime' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'pgsql' => [
            'date' => 'yyyy-MM-dd',
            'time' => 'HH:mm:ss',
            'datetime' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'default' => [
            'date' => 'yyyy-MM-dd',
            'time' => 'HH:mm:ss',
            'datetime' => 'yyyy-MM-dd HH:mm:ss',
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->logicalFormat === null) {
            $format = $this->type . 'Format';
            $pattern = Yii::$app->formatter->{$format};
        } else {
            $pattern = $this->logicalFormat;
        }

        if (substr($pattern, 0, 4) === 'php:') {
            $this->_phpLogicalFormat = substr($pattern, 4);
        } else {
            $this->_phpLogicalFormat = FormatConverter::convertDateIcuToPhp($pattern, $this->type);
        }

        if ($this->physicalFormat === null) {
            $driverName = Yii::$app->db->driverName;
            if (isset(static::$dbDatetimeFormat[$driverName])) {
                $pattern = static::$dbDatetimeFormat[$driverName][$this->type];
            } else {
                $pattern = static::$dbDatetimeFormat['default'][$this->type];
            }
        } else {
            $pattern = $this->physicalFormat;
        }

        if (substr($pattern, 0, 4) === 'php:') {
            $this->_phpPhysicalFormat = substr($pattern, 4);
        } else {
            $this->_phpPhysicalFormat = FormatConverter::convertDateIcuToPhp($pattern, $this->type);
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function convertToLogical($value, $attribute)
    {
        if ($this->isEmpty($value)) {
            return null;
        }
        $date = @date_create_from_format($this->_phpPhysicalFormat, $value);

        return $date === false ? null : $date->format($this->_phpLogicalFormat);
    }

    /**
     * @inheritdoc
     */
    protected function convertToPhysical($value, $attribute)
    {

        if ($this->isEmpty($value)) {
            return null;
        }
        $date = @date_create_from_format($this->_phpLogicalFormat, $value);

        return $date === false ? null : $date->format($this->_phpPhysicalFormat);
    }
}