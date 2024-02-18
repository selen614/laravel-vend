<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with('company')->paginate(4);

        $productName = $request->input('product_name');
        $companyName = $request->input('company_id');

        if ($productName || $companyName) {
            $query = Product::query();

            if ($productName) {
                $query->where('product_name', 'LIKE', '%' . $productName . '%');
            }

            if ($companyName) {
                $query->whereHas('company', function ($query) use ($companyName) {
                    $query->where('company_id', $companyName);
                });
            }

            $products = $query->paginate(4);
        }

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
            ]);

            DB::commit();
            return redirect()->route('index')->with('message', '登録が完了しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->route('index')->with('message', '失敗しました');
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
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            $request->validate([
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'product_name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'company_id' => 'required|exists:companies,id',
                'comment' => 'nullable|string',
            ]);

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

            DB::commit();
            return redirect()->route('index')->with('message', '更新が完了しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->route('index')->with('message', '失敗しました');
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
