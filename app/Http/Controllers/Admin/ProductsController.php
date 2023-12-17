<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function products() {
        $data['products'] = Product::get();
        return view('admin.product.index', $data);
    }

    public function store() {
        $data['user_id'] = Auth::user()->id;
        dd($data);
        DB::beginTransaction();
        try {
            DB::commit();
            return redirect()->routes('admin.products');
        }catch(\Exception $e) {
            DB::rollBack();
            return back()-with('error', $e->getMessage());
        }
    }
}
