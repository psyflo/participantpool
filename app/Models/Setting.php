<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'value'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['default'];

    /**
     * Default environment setting value
     */
    public function getDefaultAttribute()
    {
        return config($this->key);
    }

    /**
     * Get custom configuration value from database
     *
     * @param string $key
     * 
     * @return mixed
     */
    public static function get(string $key)
    {
        /*
         * Load custom configuration value
         */
        $value = Setting::where('key', $key)->value('value');

        // if ($value !== null)
        // {
        //     /*
        //      * Cast value
        //      * 
        //      * See https://www.php.net/manual/en/function.settype.php
        //      */
        //     settype($value, 'string');
        // }

        return $value;
    }
}
