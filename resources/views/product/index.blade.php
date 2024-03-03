@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>

    <!-- 検索欄 -->
    <div>
        <form action="{{ route('index') }}" method="GET" class="col-md-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="product_name">商品名部分一致検索:</label>
                        <input type="text" name="product_name" id="product_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for=" company_name">メーカー名:</label>
                        <select id="company_id" class="form-control" name="company_id">
                            <option value="" selected>すべて</option>
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="min_price">最低価格：</label>
                        <input type="number" name="min_price" id="min_price" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="max_price">最高価格：</label>
                        <input type="number" name="max_price" id="max_price" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="min_stock">最低在庫数：</label>
                        <input type="number" name="min_stock" id="min_stock" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="max_stock">最高在庫数：</label>
                        <input type="number" name="max_stock" id="max_stock" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4" style="margin: 1rem 0 0 0;">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </form>
    </div>
    <!-- 商品一覧 -->
    <div id="productsContainer">
        <table id="sort" class="table table-striped tablesorter">
            <thead>
                <tr>
                    <th class="sorter">ID</th>
                    <th>商品画像</th>
                    <th class="sorter">商品名</th>
                    <th class="sorter">価格</th>
                    <th class="sorter">在庫数</th>
                    <th class="sorter">メーカー名</th>
                    <th>
                        <a href="{{ route('create') }}" class="btn btn-primary">新規登録</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
<<<<<<< HEAD
                <tr id="product_{{ $product->id }}">
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="" style="height:80px;"></td>
=======
                <tr>
                    <td>{{ $product->id }}</td> 
                    <td><img src="{{ asset($product->img_path) }}" alt="" style="height:100px;"></td>
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <div>
                            <a href="{{ route('show', $product->id) }}" class="btn btn-info btn-sm mx-1">
                                詳細
                            </a>
                        </div>
                        <div>
                            <form action="{{ route('destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="削除" class="btn btn-danger btn-sm mx-1 delete-product" data-product-id="{{ $product->id }}">
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
