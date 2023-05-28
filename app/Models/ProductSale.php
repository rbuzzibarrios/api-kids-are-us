<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSale extends Model
{
    use HasFactory, SoftDeletes;

    //region relations

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //endregion
}
