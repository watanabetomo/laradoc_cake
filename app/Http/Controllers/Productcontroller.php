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

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $product = new Product;
            $product->where('id', $id)->delete();
            $productDetail = new ProductDetail;
            $productDetail->where('product_id', $id)->delete();
            DB::commit();
        } catch (PDOException $e) {
            DB::rollBack();
            $error = '削除に失敗しました。';
            return view('product_list', compact('error'));
        }
        $products = $product->all();
        return view('product_list', compact('products'));
    }

    public function sort(Request $request, $column)
    {
        try {
            $product = new Product;
            $products = $product->orderByRaw($column . ' IS NULL ASC')->orderBy($column, $request->input('order'))->get();
            return view('product_list', compact('products'));
        } catch (PDOException $e) {
            $error = 'データベースに接続できませんでした。';
            return view('product_list', compact('error'));
        }
    }

    public function search(Request $request)
    {
        try {
            $params = $request->all();
            $product = new Product;
            if (isset($params['all']) or $params['keyword'] == '') {
                $products = $product->all();
                return view('product_list', compact('products'));
            }
            $products = $product->where('name', 'like', '%' . $params['keyword'] . '%')->get();
            return view('product_list', compact('products'));
        } catch (PDOException $e) {
            $error = 'データベースに接続できませんでした。';
            return view('product_list', compact('error'));
        }
    }

    public function edit($action, $id = null)
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
                $productData['img'] = $productData[0]->img;
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

    public function fix(Request $request, $action, $id = null)
    {
        try {
            $productCategory = new ProductCategory;
            $productCategories = $productCategory->all();
            $params = $request->all();
            $productData['id'] = $id;
            $productData['name'] = $params['name'];
            $productData['product_category_id'] = $params['product_category_id'];
            $productData['delivery_info'] = $params['delivery_info'];
            $productData['turn'] = $params['turn'];
            for ($i = 0; $i < 5; $i++) {
                $detail['size'] = $params['details'][$i]['size'];
                $detail['price'] = $params['details'][$i]['price'];
                $productData['details'][$i] = $detail;
            }
            return view('product_edit', compact('productCategories', 'productData', 'action'));
        } catch (PDOException $e){
            $error = 'データベースに接続できませんでした。';
            return view('product_edit', compact('error', 'action'));
        }
    }

    public function display(Request $request, $action, $id = null)
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

    public function register(Request $request, $action, $id = null)
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

    public function upload(Request $request, $id){
        try {
            $product = new Product;
            $products = $product->select('*')->where('id', $id)->get();
            $products[0]->img = $request->input('img');
            $products[0]->save();
            $action = 'edit';
            $productCategory = new ProductCategory;
            $productCategories = $productCategory->all();
            $productData = $product->where('id', $id)->get();
            $productDetail = new ProductDetail;
            $productDetail = $productDetail->select('*')->where('product_id', $id)->get();
            for ($i = 0; $i < 5; $i++) {
                $detail['size'] = $productDetail[$i]->size;
                $detail['price'] = $productDetail[$i]->price;
                $index[$i] = $detail;
                $productData['details'] = $index;
            }
            return view('product_edit', compact('productCategories', 'productData', 'action'));
        } catch (PDOException $e) {
            $error = 'データベースに接続できませんでした。';
            return view('product_edit', compact('error', 'action'));
        }
    }
}
