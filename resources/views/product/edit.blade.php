@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">更新画面</h1>
    <div>
        @if(session('flash_message'))
        <div>
            {{ session('flash_message') }}
        </div>
        @endif
        @if(session('flash_error_message'))
        <div>
            {{ session('flash_error_message')}}
        </div>
        @endif
    </div>

    <a href="{{ route('index') }}" class="btn btn-primary mb-3">商品一覧に戻る</a>

    <form method="POST" action="{{ route('update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="id" class="form-label col-8 margin-auto">ID:</label>
            <p class="auto col-4">{{ $product->id }}</p>
        </div>

        <div class="mb-3">
            <label for="current_img_path" class="form-label">現在の商品画像:</label>
            <img src="{{ asset($product->img_path) }}" alt="現在の商品画像" style="max-width: 200px;">
        </div>
        <div>
            <label for="img_path" class="form-label">新しい商品画像:</label>
            <input id="img_path" type="file" name="img_path" class="form-control" value="{{ asset($product->img_path) }}">
        </div>

        <div class="mb-3">
            <label for="product_name" class="form-label">商品名:</label>
            <input id="product_name" type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">価格:</label>
            <input id="price" type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">在庫数:</label>
            <input id="stock" type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>

        <div class="mb-3">
            <label for="company" class="form-label">メーカー名</label>
            <select class="form-select" id="company_id" name="company_id">
                @foreach($companies as $company)
                <option value="{{ $company->id }}" @if($company->id == $product->company_id) selected @endif>{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control" rows="3">{{ $product->comment }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection