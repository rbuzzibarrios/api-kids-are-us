<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'product_category_id',
        'tags',
        'description',
        'additional_information',
        'rate',
        'images',
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
    ];

    protected $appends = [
        'title',
    ];

    //region accessors

    public function getTitleAttribute()
    {
        return $this->name;
    }

    //endregion

    //region relations

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(ProductStock::class, 'product_id', 'id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(ProductSale::class, 'product_id', 'id');
    }

    //endregion
}
