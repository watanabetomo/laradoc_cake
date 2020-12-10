<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDetail;

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

    public function edit($action)
    {
        if ($action == 'new') {
            try {
                $productCategory = new ProductCategory;
                $productCategories = $productCategory->all();
                return view('product_edit', compact('productCategories', 'action'));
            } catch (PDOException $e) {
                $error = 'データベースに接続できませんでした。';
                return view('product_edit', compact('error', 'action'));
            }
        } elseif ($action == 'edit') {

        }
    }

    public function display(Request $request, $action)
    {
        if ($action == 'new') {
            try {
                $productCategory = new ProductCategory;
                $category = $productCategory->select('name')->where('id', $request->input('category_id'))->get();
                $category = $category[0]->name;
                $input = $request->all();
                return view('product_conf', compact('action', 'input', 'category'));
            } catch (PDOException $e) {
                $error = 'データベースに接続できませんでした。';
                return view('product_conf', compact('error', 'action'));
            }
        }
    }

    public function register(Request $request, $action)
    {
        $params = $request->all();
        $user = session()->get('user_id');
        if ($action == 'new') {
            $error = DB::transaction(function () use ($params, $user) {
                $product = new Product;
                $id = $product->insertGetId([
                    'name' => $params['name'],
                    'product_category_id' => $params['product_category_id'],
                    'delivery_info' => $params['delivery_info'],
                    'turn' => $params['turn'],
                    'create_user' => $user,
                ]);
                $productDetail = new ProductDetail;
                for ($i = 0; $i < 5; $i++){
                    $productDetail->create([
                        'product_id' => $id,
                        'size' => $params['details'][$i]['size'],
                        'price' => $params['details'][$i]['price'],
                        'turn' => $i
                    ]);
                }
                return view('product_done');
            });
            return view('product_done', compact('error'));
        }
    }
}
