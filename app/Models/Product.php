<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_category_id',
        'img',
        'delivery_info',
        'turn',
        'create_user',
        'update_user',
        'created_at',
        'updated_at',
        'delete_flg'
    ];
}
