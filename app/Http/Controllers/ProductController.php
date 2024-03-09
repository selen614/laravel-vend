<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productName = $request->input('product_name');
        $companyId = $request->input('company_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minStock = $request->input('min_stock');
        $maxStock = $request->input('max_stock');

        $query = Product::with('company');

        if ($productName) {
            $query->where('product_name', 'LIKE', '%' . $productName . '%');
        }

        if ($companyId) {
            $query->whereHas('company', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }

        if ($minPrice && $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        } elseif ($minPrice) {
            $query->where('price', '>=', $minPrice);
        } elseif ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($minStock && $maxStock) {
            $query->whereBetween('stock', [$minStock, $maxStock]);
        } elseif ($minStock) {
            $query->where('stock', '>=', $minStock);
        } elseif ($maxStock) {
            $query->where('stock', '<=', $maxStock);
        }

        $query->orderBy('id', 'desc');

        $products = $query->paginate(4);

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('product.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'product_name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('img_path')) {
                $imagePath = $request->file('img_path')->store('public/images');
                $imagePath = Storage::url($imagePath);
            }

            $product = Product::create([
                'company_id' => $validatedData['company_id'],
                'product_name' => $validatedData['product_name'],
                'price' => $validatedData['price'],
                'stock' => $validatedData['stock'],
                'comment' => $validatedData['comment'],
                'img_path' => $imagePath,
            ]);

            return redirect()->route('index')->with('success', '作成しました');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->route(['message' => '失敗しました'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $companies = Company::all();
        return view('product.edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'product_name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('img_path')) {
                $imagePath = $request->file('img_path')->store('public/images');
                $imagePath = Storage::url($imagePath);
            }

            $product = Product::findOrFail($id);
            $product->company_id = $validatedData['company_id'];
            $product->product_name = $validatedData['product_name'];
            $product->price = $validatedData['price'];
            $product->stock = $validatedData['stock'];
            $product->comment = $validatedData['comment'];
            $product->img_path = $imagePath;
            $product->save();

            return redirect()->route('index')->with('success', '更新しました');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->route(['message' => '失敗しました'], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(['message' => '削除しました'], 200);
    }
}
