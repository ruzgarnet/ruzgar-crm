<?php

namespace App\Models;

use App\Models\Attributes\OptionFieldsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, OptionFieldsAttribute;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array'
    ];

    /**
     * ContractType Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

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
     * 2 => Disabled
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

    /**
     * Service Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
