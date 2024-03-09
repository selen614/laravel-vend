<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->product_id);

            if ($product->stock < $request->quantity) {
                throw new \Exception('在庫が不足しています');
            }

            $sale = new Sale();
            $sale->product_id = $product->id;
            $sale->quantity = $request->quantity;
            $sale->save();

            $product->stock -= $request->quantity;
            $product->save();

            DB::commit();

            return response()->json(['message' => '購入が完了しました'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return response()->json(['message' => '商品が見つかりませんでした'], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
