<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * Get value for required key
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        return self::find($key)->value ?? ($default ?? $key);
    }
}
