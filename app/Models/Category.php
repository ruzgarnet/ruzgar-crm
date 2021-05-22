<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get categorytype id's
     * For titles use language files => tables.{table_name|model_name}.types.{type_id}
     * 1 => Product
     * 2 => Service
     *
     * @param bool $implode
     * @return array
     */
    public static function getTypes($implode = false)
    {
        $data = [1, 2];
        return $implode ? implode(',', $data) : $data;
    }

    /**
     * Get category status id's
     * For titles use language files => tables.{table_name|model_name}.status.{status_id}
     * 1 => Active
     *
     * @param bool $implode
     * @return array
     */
    public static function getStatus($implode = false)
    {
        $data = [1];
        return $implode ? implode(',', $data) : $data;
    }

    /**
     * Get parent category
     *
     * @return \App\Models\Category|null
     */
    public function getParentAttribute()
    {
        if ($this->parent_id) {
            return $this->find($this->parent_id);
        }
        return null;
    }
}
