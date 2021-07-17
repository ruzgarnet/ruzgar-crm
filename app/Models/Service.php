<?php

namespace App\Models;

use App\Models\Attributes\PriceAttribute;
use App\Models\Attributes\ProductSelectPrintAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, PriceAttribute, ProductSelectPrintAttribute;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Category Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get option if is exists by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $key, $default = false)
    {
        if (
            $this->options != null &&
            is_array($this->options) && 
            is_string($key) &&
            isset($this->options[$key]) &&
            !empty($this->options[$key])
        ) {
            return $this->options[$key];
        }
        return $default;
    }
}
