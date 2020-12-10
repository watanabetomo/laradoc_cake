@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="css/admin_product.css">
<main>
    @if (isset($error))
        <h2 class="done error">{{$error}}</h2>
    @else
        <h2 class="done">登録が完了しました</h2>
    @endif
</main>
@endsection
