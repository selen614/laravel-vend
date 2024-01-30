<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Product;
use App\Models\Sale;


class Company extends Model
{
    use HasFactory;
    /**
     * Get the sales associated with the company.
     */
    protected $fillable = ['company_name', 'street_address', 'representative_name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'company_id');
    }
}
