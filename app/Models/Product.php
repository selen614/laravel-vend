<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public static function createProduct($data)
    {
        if (isset($data['img_path'])) {
            $imagePath = Storage::putFile('public/images', $data['img_path']);
            $data['img_path'] = Storage::url($imagePath);
        }

        return self::create($data);
    }

    public function updateProduct($data)
    {
        if (isset($data['img_path'])) {
            $imagePath = Storage::putFile('public/images', $data['img_path']);
            $data['img_path'] = Storage::url($imagePath);
        }

        $this->update($data);
    }
}
