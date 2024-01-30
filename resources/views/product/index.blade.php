@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>

    <!-- 検索欄 -->
    <div class="row">
        <form action="{{ route('index') }}" method="GET" class="col-md-12">
            <div class="form-group col-md-4">
                <label for="product_name">商品名部分一致検索:</label>
                <input type="text" name="product_name" id="product_name" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label for="company_name">メーカー名:</label>
                <select name="company_id">
                    <option value="" selected>すべて</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </form>
    </div>
    <!-- 商品一覧 -->
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th>
                        <a href="{{ route('create') }}" class="btn btn-primary">新規登録</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像"></td>
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
                                <input type="submit" value="削除" class="btn btn-danger btn-sm mx-1">
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