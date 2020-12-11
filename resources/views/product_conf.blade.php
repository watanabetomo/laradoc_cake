@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('css/admin_product.css')}}">
<main>
    <p class="error">{{isset($error) ? $error : ''}}</p>
    <table border="1">
        @if ($action == 'edit')
            <tr>
                <th>ID</th>
                <td colspan="3">{{$input['id']}}</td>
            </tr>
        @endif
        <tr>
            <th>商品名</th>
            <td colspan="3">{{$input['name']}}</td>
        </tr>
        <tr>
            <th>商品カテゴリー</th>
            <td colspan="3">{{$category}}</td>
        </tr>
        <tr>
            <th>配送情報</th>
            <td colspan="3">{{$input['delivery_info']}}</td>
        </tr>
        <tr>
            <th>表示順(商品)</th>
            <td colspan="3">{{$input['turn']}}</td>
        </tr>
        <tr>
            <th rowspan="6">商品詳細</th>
            <th>表示順(商品詳細)</th>
            <th>サイズ(cm)</th>
            <th>価格(円)</th>
        </tr>
        @for ($i = 0; $i < 5; $i++)
            <tr>
                <td>{{$i}}</td>
                <td>{{$input['details'][$i]['size']}}</td>
                <td>{{$input['details'][$i]['price']}}</td>
            </tr>
        @endfor
    </table>
    <form action="/product_done/{{$action}}{{isset($input['id']) ? '/' . $input['id'] : ''}}" method="post">
        @csrf
        <input type="hidden" name="name" value="{{$input['name']}}">
        <input type="hidden" name="product_category_id" value="{{$input['category_id']}}">
        <input type="hidden" name="delivery_info" value="{{$input['delivery_info']}}">
        <input type="hidden" name="turn" value="{{$input['turn']}}">
        @for ($i = 0; $i < 5; $i++)
            <input type="hidden" name="details[{{$i}}][size]" value="{{$input['details'][$i]['size']}}">
            <input type="hidden" name="details[{{$i}}][price]" value="{{$input['details'][$i]['price']}}">
        @endfor
        <p class="submit-button register-btn"><input type="submit" name="register" class="btn" value="{{($action == 'edit' ? '更新' : '登録')}}完了する"></p>
    </form>
    <form action="/product_edit/{{$action}}{{isset($input['id']) ? '/' . $input['id'] : ''}}" method="post">
        @csrf
        <input type="hidden" name="name" value="{{$input['name']}}">
        <input type="hidden" name="product_category_id" value="{{$input['category_id']}}">
        <input type="hidden" name="delivery_info" value="{{$input['delivery_info']}}">
        <input type="hidden" name="turn" value="{{$input['turn']}}">
        @for ($i = 0; $i < 5; $i++)
            <input type="hidden" name="details[{{$i}}][size]" value="{{$input['details'][$i]['size']}}">
            <input type="hidden" name="details[{{$i}}][price]" value="{{$input['details'][$i]['price']}}">
        @endfor
        <p class="submit-button"><input type="submit" name="fix" class="btn" value="修正する"></p>
    </form>
</main>
@endsection
