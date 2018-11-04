<?php

namespace BlueRestAPI\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $amount
 * @property string $name
 *
 * Class Item
 * @package BlueRestAPI\Model
 */
class Item extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'blue_items';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];
}