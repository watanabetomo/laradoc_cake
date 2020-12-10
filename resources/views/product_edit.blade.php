@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="css/admin_product.css">
<main>
    <form action="product_conf" method="post">
        @if (isset($error))
            <p class="error">{{$error}}</p>
        @endif
        <table border="1">
            @if (preg_match('/edit$/', $_SERVER['REQUEST_URI']))
                <tr>
                    <th>
                        ID
                    </th>
                    <td colspan="5">
                        {{$id}}
                    </td>
                </tr>
            @endif
            <tr>
                <th>
                    商品名
                </th>
                <td colspan="5">
                    <input type="text" name="name" value="{{isset($productData['name']) ? h($productData['name']) : ''}}">
                </td>
            </tr>
            <tr>
                <th>
                    商品カテゴリー
                </th>
                <td colspan="5">
                    <select name="category_id">
                        @foreach ($productCategories as $category)
                            <option value="{{$category['id']}}"{{(isset($productData['product_category_id']) and $productData['product_category_id'] == $category['id']) ? ' selected' : ''}}>{{$category['name']}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    配送情報
                </th>
                <td colspan="5">
                    <input type="text" name="delivery_info" value="{{isset($productData['delivery_info']) ? h($productData['delivery_info']) : ''}}">
                </td>
            </tr>
            <tr>
                <th>
                    表示順(商品)
                </th>
                <td colspan="5">
                    <input type="number" name="turn" value="{{isset($productData['turn']) ? h($productData['turn']) : ''}}">
                </td>
            </tr>
            <tr>
                <th rowspan="6">
                    商品詳細
                </th>
                <th>
                    表示順(商品詳細)
                </th>
                <th>
                    サイズ(cm)
                </th>
                <th>
                    価格(円)
                </th>
            </tr>
            @for ($i = 0; $i < 5; $i++)
                <tr>
                    <td>
                        {{$i}}
                    </td>
                    <td>
                        <input type="number" min="1" max="65535" name="details[{{$i}}][size]" value="{{isset($productData['details'][$i]['size']) ? h($productData['details'][$i]['size']) : ''}}">
                    </td>
                    <td>
                        <input type="number" min="0" max="4294967295" name="details[{{$i}}][price]" value="{{isset($productData['details'][$i]['price']) ? h($productData['details'][$i]['price']) : ''}}">
                    </td>
                </tr>
            @endfor
        </table>
        <p class="submit-button"><input type="submit" name="send" class="btn" value="確認画面へ"></p>
    </form>
    @if (preg_match('/edit$/', $_SERVER['REQUEST_URI']))
        <p class="error"><?=isset($fileUploadError) ? $fileUploadError : ''?></p>
        <form id="upload" action="" method="post" enctype="multipart/form-data" onsubmit="return confirm('本当に画像をアップロードしますか？')">
            <table border="1" style="margin-top: 70px;">
                <tr>
                    <th>ファイル選択</th>
                    <td><input type="file" name="img"></td>
                </tr>
                <tr>
                    <th>画像</th>
                    <td><?=isset($productData['img']) ? '<img src="../' . IMG_PATH . $productData['img'] . '" alt="' . $productData['img'] . '"' : ''?></td>
                </tr>
            </table>
            <p class="submit-button"><input type="submit" class="btn" name="upload" value="登録"></p>
        </form>
    @endif
</main>
@endsection
