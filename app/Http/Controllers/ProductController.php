<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
<<<<<<< HEAD
use Illuminate\Support\Facades\Storage;
=======
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
<<<<<<< HEAD
        $productName = $request->input('product_name');
        $companyId = $request->input('company_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minStock = $request->input('min_stock');
        $maxStock = $request->input('max_stock');
=======
        $products = Product::with('company')->paginate(4);

        $productName = $request->input('product_name');
        $companyName = $request->input('company_id');
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938

        $query = Product::with('company');

<<<<<<< HEAD
        if ($productName) {
            $query->where('product_name', 'LIKE', '%' . $productName . '%');
=======
            if ($productName) {
                $query->where('product_name', 'LIKE', '%' . $productName . '%');
            }

            if ($companyName) {
                $query->whereHas('company', function ($query) use ($companyName) {
                    $query->where('company_id', $companyName);
                });
            }

            $products = $query->paginate(4);
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938
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
<<<<<<< HEAD
            $validatedData = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'product_name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
=======
            DB::beginTransaction();

            $image = $request->file('img_path');
            if ($image) {
                $file_name = $image->getClientOriginalName();
                $image->storeAs('public/images', $file_name);
                $image_path = 'storage/images/' . $file_name;
            } else {
                $image_path = null;
            }
            Product::create([
                'company_id' => $request->company_id,
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'img_path' => $image_path,
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938
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

            return response()->json($product, 201);
        } catch (\Exception $e) {
            Log::error($e);
<<<<<<< HEAD
            return response()->json(['message' => '失敗しました'], 500);
=======
            return redirect()->route('index')->with('message', '失敗しました');
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938
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

<<<<<<< HEAD
            $imagePath = null;
            if ($request->hasFile('img_path')) {
                $imagePath = $request->file('img_path')->store('public/images');
                $imagePath = Storage::url($imagePath);
            }
=======
            $image = $request->file('img_path');
            if ($image) {
                $file_name = $image->getClientOriginalName();
                $image->storeAs('public/images', $file_name);
                $image_path = 'storage/images/' . $file_name;
            } else {
                $image_path = $product->img_path;
            }
            $changes = $request->only(['company_id', 'product_name', 'price', 'stock', 'comment']);
            $changes['img_path'] = $image_path;

            $product->update($changes);
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938

            $product = Product::findOrFail($id);
            $product->company_id = $validatedData['company_id'];
            $product->product_name = $validatedData['product_name'];
            $product->price = $validatedData['price'];
            $product->stock = $validatedData['stock'];
            $product->comment = $validatedData['comment'];
            $product->img_path = $imagePath;
            $product->save();

            return response()->json($product, 200);
        } catch (\Exception $e) {
            Log::error($e);
<<<<<<< HEAD
            return response()->json(['message' => '失敗しました'], 500);
=======
            return redirect()->route('index')->with('message', '失敗しました');
>>>>>>> 32f282825bd5fa435ba4fc3d1ee9fa50ecb0d938
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
        return redirect()->route('index')->with('success', '削除しました');
    }
}
