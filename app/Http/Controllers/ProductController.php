<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;


class ProductController extends BaseController
{
    public function index(){
        $product = Product::all();
        return $this->sendResponse(ProductResource::collection($product), "Ok");
    }
    public function store(Request $request){
        $input=$request->all();
        $validator = Validator::make($input,[
            "name"=>"required",
            "price"=>"required"
        ]);
        if ($validator->fails()){
            return $this->senError($validator->errors());
            //print_r("hiba");
        }
        $product = Product::create($input);
        return $this->sendResponse($product, "Post létrehozva");
        //print_r("Létrehozva");
    }

    public function show($id){
        $product= Product::find($id);

        if(is_null($product)){
            return $this->sendError("Nem létezik");
        }
        return $this->sendResponse(new ProductResource($product), "betöltve");
    }
    public function update(Request $request, $id){
        $input = $request-> all();

        $validator = Validator::make($input,[
            "name"=>"required",
            "price"=> "required"
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());

        }
        $product = Product::find($id);
        $product -> update($request->all());

        return $this->sendResponse(new ProductResource($product), "Frissítve");
    }
    public function destroy(Product $id){
        $id->delete($id);
        return $this->sendResponse([], "Törölve");
    }
}
