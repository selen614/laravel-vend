@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品詳細画面</h1>

    <a href="{{ route('index') }}" class="btn btn-primary mb-3">商品一覧に戻る</a>

    <div class="mb-3">
        <label for="id" class="form-label col-8 margin-auto">ID:</label>
        <p class="border 2px auto col-4">{{ $product->id }}</p>
    </div>

    <div class="mb-3">
        <label for="img_path" class="form-label col-8 margin-auto">商品画像:</label>
        <img src="{{ $product->img_path }}" alt="" class="border 2px auto col-4">
        <div>
            @if($product->img_path)
            <img src="{{ $product->img_path }}" alt="商品画像">
            @else
            画像なし
            @endif
        </div>
    </div>

    <div class="mb-3">
        <label for="product_name" class="form-label col-8 margin-auto">商品名:</label>
        <p class="border 2px auto col-4">{{ $product->product_name }}</p>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label col-8 margin-auto">価格:</label>
        <p class="border 2px auto col-4">{{ $product->price }}円</p>
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label col-8 margin-auto">在庫数:</label>
        <p class="border 2px auto col-4">{{ $product->stock }}本</p>
    </div>

    <div class="mb-3">
        <label for="company" class="form-label col-8 margin-auto">メーカー名:</label>
        <p class="border 2px auto col-4">{{ $product->company->company_name }}</p>
    </div>

    <div class="mb-3">
        <label for="comment" class="form-label col-8 margin-auto">コメント:</label>
        <p class="border 2px auto col-4">{{ $product->comment }}</p>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('edit', $product->id) }}" class="btn btn-primary">更新画面へ</a>
    </div>
</div>

@endsection
