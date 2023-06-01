<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'category',
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    protected $appends = [
        'title',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function getRouteKeyName(): string
    {
        return 'sku';
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (Product $product) {
            $product->stock()->create(request()->only('quantity'))->save();
        });

        static::updated(function (Product $product) {
            if (request()->request->has('quantity')) {
                $product->stock()->update(request()->only('quantity'));
            }
        });
    }

    //region scopes

    public function scopeSold(Builder $query)
    {
        $query->has('sales');
    }

    //endregion

    //region accessors and mutators

    public function getTitleAttribute(): string|null
    {
        return $this->name;
    }

    public function setCategoryAttribute(mixed $value): void
    {
        $this->attributes['product_category_id'] = $value;
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
