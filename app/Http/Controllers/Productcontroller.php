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

    public function edit($action, $id)
    {
        try {
            $productCategory = new ProductCategory;
            $productCategories = $productCategory->all();
            if ($action == 'new') {
                return view('product_edit', compact('productCategories', 'action'));
            } elseif ($action == 'edit') {
                $product = new Product;
                $productData = $product->select('*')->where('id', $id)->get();
                $productData['id'] = $productData[0]->id;
                $productData['name'] = $productData[0]->name;
                $productData['product_category_id'] = $productData[0]->product_category_id;
                $productData['delivery_info'] = $productData[0]->delivery_info;
                $productData['turn'] = $productData[0]->turn;
                $productDetail = new ProductDetail;
                $productDetail = $productDetail->select('*')->where('product_id', $id)->get();
                for ($i = 0; $i < 5; $i++) {
                    $detail['size'] = $productDetail[$i]->size;
                    $detail['price'] = $productDetail[$i]->price;
                    $index[$i] = $detail;
                    $productData['details'] = $index;
                }
                return view('product_edit', compact('productCategories', 'productData', 'action'));
            }
        } catch (PDOException $e) {
            $error = 'データベースに接続できませんでした。';
            return view('product_edit', compact('error', 'action'));
        }
    }

    public function display(Request $request, $action, $id)
    {
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

    public function register(Request $request, $action, $id)
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
        } elseif ($action == 'edit') {
            $error = DB::transaction(function () use ($params, $user, $id) {
                $product = new Product;
                $product = $product->select('*')->where('id', $id)->get();
                $product[0]->name = $params['name'];
                $product[0]->product_category_id = $params['product_category_id'];
                $product[0]->delivery_info = $params['delivery_info'];
                $product[0]->turn = $params['turn'];
                $product[0]->update_user = $user;
                $product[0]->save();
                $productDetail = new ProductDetail;
                $productDetail = $productDetail->select('*')->where('product_id', $id)->get();
                for ($i = 0; $i < 5; $i++) {
                    $productDetail[$i]->size = $params['details'][$i]['size'];
                    $productDetail[$i]->price = $params['details'][$i]['price'];
                    $productDetail[$i]->save();
                };
                return view('product_done');
            });
            return view('product_done', compact('error'));
        }
    }
}
