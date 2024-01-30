<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    public function edit(User $user, Product $product)
    {
        // Your logic for determining if the user can edit the product
        return $user->id === $product->user_id;
    }
}
