<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class Productcontroller extends Controller
{
    public function showList()
    {
        try {
            $product = new Product;
            $products = $product->all();
            return view('product_list', compact('products'));
        } catch (PDOException $e) {
            $error = 'データベースに接続できませんでした。';
            return view('product_list', compact('error'));
        }
    }

    public function register()
    {
        try {
            $productCategory = new ProductCategory;
            $productCategories = $productCategory->all();
            return view('product_edit', compact('productCategories'));
        } catch (PDOException $e) {
            $error = 'データベースに接続できませんでした。';
            return view('product_edit', compact('error'));
        }
    }
}
