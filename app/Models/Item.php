<?php

namespace App\Models;

use App\Services\Helpers\UploadFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes, HasFactory, UploadFile;

    protected $table = 'items';

    protected $fillable = [
        'title',
        'description',
        'picture_url',
        'category_id',
        'unit_price',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'picture_url' => 'string',
        'category_id' => 'string',
        'unit_price' => 'float',
    ];

    public function setPictureUrlAttribute(string $value): void
    {
        $fileUrl = $this->uploadBase64File('payments/items',  $value);

        $this->attributes['picture_url'] = $fileUrl;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'item_id', 'id');
    }
}
