<?php

namespace App\Models\Payment;

use App\Models\Model;
use App\Services\Helpers\UploadFile;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentItem extends Model
{
    use SoftDeletes;

    protected $table = 'payments_items';

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
        $uploadFile = app(UploadFile::class);

        $fileName = rand(10, 100).time();

        $fileUrl = $uploadFile->uploadBase64File('payments/items', $fileName, $value);

        $this->attributes['picture_url'] = $fileUrl;
    }
}
