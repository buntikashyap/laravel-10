<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductContrller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        try {
            $products = Product::orderBy('id', 'desc')->paginate(2);
            return response()->json(['status' => true, 'message' => 'Data get successfully', 'data' => $products]);
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>false, 'message'=>$validator->errors()]);
        }
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $product = new Product();
            $product->fill([
                'user_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description
            ]);
            $product->save();
            $product = Product::find($product->id);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Product created successfully', 'data' => $product]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($id) {
        try {
            $product = Product::find($id);
            return response()->json(['status' => true, 'message' => 'Product get successfully', 'data' => $product]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $product = Product::find($id);
            if(!$product) {
                return response()->json(['status' => false, 'message' => 'Product not found']);
            }
            $product->delete();
            return response()->json(['status' => true, 'message' => 'Product delete successfully']);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Product delete successfully']) ;
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>false, 'message'=>$validator->errors()]);
        }
        DB::beginTransaction();
        try {
            $product = Product::find($id) ;
            if(!$product) {
                return response()->json(['status' => false, 'message' => 'Product not found']);
            }
            $product->fill([
                'name' => $request->name,
                'image' => $request->image, 
                'description' => $request->description,
            ]);
            $product->update();
            $product = Product::find($id);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Product udpate successfully', 'data' => $product]);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }
}
