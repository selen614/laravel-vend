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
        $products = Product::with('company')->paginate(5);

        $productName = $request->input('product_name');
        $companyName = $request->input('company_id'); // Ensure this matches the name in the form

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

            $products = $query->paginate(5);
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
        // dd($companies);
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

            Product::create([
                'company_id' => $request->company_id,
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'img_path' => $request->img_path,
            ]);

            DB::commit();
            return redirect()->route('index')->with('message', '登録が完了しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            $message = "エラーが発生しました: " . $e->getMessage();
            return view('index', compact('message'));
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

            $product->update([
                'company_id' => $request->company_id,
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'img_path' => $request->img_path,
            ]);

            DB::commit();
            return redirect()->route('index')->with('message', '更新が完了しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            $message = "エラーが発生しました: " . $e->getMessage();
            return response()->view('index', compact('message'), 500);
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
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
